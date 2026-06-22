import 'dart:io';
import 'dart:typed_data';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:path_provider/path_provider.dart';
import 'package:share_plus/share_plus.dart';
import 'package:pdf/pdf.dart';
import 'package:pdf/widgets.dart' as pw;
import '../../../core/theme/app_theme.dart';
import '../../auth/providers/auth_provider.dart';
import '../../../core/network/api_service.dart';
import 'package:intl/intl.dart';

class SellerAnalyticsScreen extends ConsumerStatefulWidget {
  const SellerAnalyticsScreen({super.key});

  @override
  ConsumerState<SellerAnalyticsScreen> createState() => _SellerAnalyticsScreenState();
}

class _SellerAnalyticsScreenState extends ConsumerState<SellerAnalyticsScreen> {
  String _period = 'Hari Ini';
  final ApiService _api = ApiService();
  
  Map<String, dynamic>? _analytics;
  List<dynamic>? _recentTransactions;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _fetchData();
  }

  Future<void> _fetchData() async {
    try {
      final res = await _api.get('/seller/analytics/summary');
      final resOrders = await _api.get('/seller/orders'); // Fetch all orders for recent transactions
      
      if (mounted) {
        setState(() {
          _analytics = res.data;
          _recentTransactions = resOrders.data['data'];
          _isLoading = false;
        });
      }
    } catch (e) {
      if (mounted) setState(() => _isLoading = false);
    }
  }

  String _fmt(num val) {
    if (val >= 1000000) return '${(val / 1000000).toStringAsFixed(1)}jt';
    if (val >= 1000) return '${(val / 1000).toStringAsFixed(0)}k';
    return val.toString();
  }

  String _fmtFull(num val) {
    final formatter = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp', decimalDigits: 0);
    return formatter.format(val);
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) {
       return const Scaffold(backgroundColor: Color(0xFFF8EFDE), body: Center(child: CircularProgressIndicator(color: AppColors.primary)));
    }

    final today = _analytics?['today'] ?? {};
    final last30 = _analytics?['last_30_days'] ?? {};
    final thisWeek = _analytics?['this_week'] ?? {};
    final thisMonth = _analytics?['this_month'] ?? {};

    Map<String, dynamic> activeData = today;
    String trendStr = "Hari ini";
    if (_period == 'Minggu Ini') {
      activeData = thisWeek;
      trendStr = "Minggu ini";
    } else if (_period == 'Bulan Ini') {
      activeData = thisMonth;
      trendStr = "Bulan ini";
    }

    final paymentSummary = _analytics?['payment_summary'] ?? {'COD': 0, 'QRIS': 0};
    final forecasting = _analytics?['forecasting'] ?? [];
    final topProducts = last30['top_products'] ?? [];

    List<dynamic> activeTransactions = (_recentTransactions ?? []).where((order) {
       if (_period == 'Bulan Ini') return true;
       final dateStr = order['created_at'] ?? DateTime.now().toIso8601String();
       final date = DateTime.parse(dateStr).toLocal();
       if (_period == 'Hari Ini') {
          return date.year == DateTime.now().year && date.month == DateTime.now().month && date.day == DateTime.now().day;
       }
       if (_period == 'Minggu Ini') {
          DateTime now = DateTime.now();
          DateTime startLimit = DateTime(now.year, now.month, now.day).subtract(const Duration(days: 6));
          return !date.isBefore(startLimit);
       }
       return true;
    }).toList();
    
    double activeCod = 0;
    double activeQris = 0;
    for (var order in activeTransactions) {
       double total = double.tryParse(order['total_amount'].toString()) ?? 0;
       String pm = order['payment_method']?.toString().toUpperCase() ?? '';
       if (pm.contains('QRIS')) activeQris += total;
       else activeCod += total;
    }

    final chartData = _analytics?['revenue_over_time'] as List? ?? [];

    List<Map<String, dynamic>> finalChartData = [];
    String chartSubtitle = "Per Hari";

    if (_period == 'Bulan Ini') {
       chartSubtitle = "Bulan Ini (4 Minggu)";
       double w1 = 0, w2 = 0, w3 = 0, w4 = 0;
       int currMonth = DateTime.now().month;
       int currYear = DateTime.now().year;

       for (var item in chartData) {
          DateTime d = DateTime.parse(item['date'].toString());
          if (d.month == currMonth && d.year == currYear) {
             double val = double.tryParse(item['total'].toString()) ?? 0;
             if (d.day <= 7) w1 += val;
             else if (d.day <= 14) w2 += val;
             else if (d.day <= 21) w3 += val;
             else w4 += val;
          }
       }
       finalChartData = [
          {'date': 'Mg 1', 'total': w1},
          {'date': 'Mg 2', 'total': w2},
          {'date': 'Mg 3', 'total': w3},
          {'date': 'Mg 4', 'total': w4},
       ];
    } else if (_period == 'Minggu Ini') {
       chartSubtitle = "Minggu Ini (7 Hari Terakhir)";
       DateTime now = DateTime.now();

       for (int i = 6; i >= 0; i--) {
          DateTime d = now.subtract(Duration(days: i));
          String matchDateStr = DateFormat('yyyy-MM-dd').format(d);
          
          double totalForDay = 0;
          for (var item in chartData) {
              if (item['date']?.toString().startsWith(matchDateStr) == true) {
                  totalForDay += double.tryParse(item['total'].toString()) ?? 0;
              }
          }
          finalChartData.add({
              'date': DateFormat('MM/dd').format(d),
              'total': totalForDay
          });
       }
    } else {
       chartSubtitle = "Hari Ini";
       finalChartData = [];
    }

    return Column(
      children: [
        // FIXED COMPACT HEADER
        Container(
          width: double.infinity,
          padding: const EdgeInsets.fromLTRB(24, 60, 24, 24),
          decoration: const BoxDecoration(
            color: AppColors.primary,
            borderRadius: BorderRadius.only(bottomLeft: Radius.circular(35), bottomRight: Radius.circular(35)),
          ),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text("Dashboard Penjual", style: GoogleFonts.inter(color: Colors.white70, fontSize: 13, fontWeight: FontWeight.w500)),
                      Text("Hayo Chicken", style: GoogleFonts.inter(color: Colors.white, fontSize: 28, fontWeight: FontWeight.w900, letterSpacing: -0.5)),
                    ],
                  ),
                  GestureDetector(
                    onTap: () {
                      showDialog(
                        context: context,
                        builder: (ctx) => Dialog(
                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(24)),
                          backgroundColor: Colors.white,
                          child: Padding(
                            padding: const EdgeInsets.all(24),
                            child: Column(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                Container(
                                  padding: const EdgeInsets.all(16),
                                  decoration: const BoxDecoration(color: Color(0xFFFDE8E8), shape: BoxShape.circle),
                                  child: const Icon(Icons.logout_rounded, color: AppColors.primary, size: 32),
                                ),
                                const SizedBox(height: 20),
                                Text("Keluar Akun?", style: GoogleFonts.inter(fontSize: 20, fontWeight: FontWeight.w900, color: const Color(0xFF1A1A1A))),
                                const SizedBox(height: 8),
                                Text(
                                  "Sesi Anda akan diakhiri. Apakah Anda yakin ingin keluar dari akun Penjual?",
                                  textAlign: TextAlign.center,
                                  style: GoogleFonts.inter(fontSize: 13, color: Colors.grey[600]),
                                ),
                                const SizedBox(height: 28),
                                Row(
                                  children: [
                                    Expanded(
                                      child: OutlinedButton(
                                        style: OutlinedButton.styleFrom(
                                          padding: const EdgeInsets.symmetric(vertical: 14),
                                          side: BorderSide(color: Colors.grey[300]!, width: 2),
                                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                                        ),
                                        onPressed: () => Navigator.pop(ctx),
                                        child: Text("Batal", style: GoogleFonts.inter(fontWeight: FontWeight.w700, color: Colors.grey[700])),
                                      ),
                                    ),
                                    const SizedBox(width: 12),
                                    Expanded(
                                      child: ElevatedButton(
                                        style: ElevatedButton.styleFrom(
                                          backgroundColor: AppColors.primary,
                                          padding: const EdgeInsets.symmetric(vertical: 14),
                                          elevation: 0,
                                          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                                        ),
                                        onPressed: () {
                                          Navigator.pop(ctx);
                                          ref.read(authProvider.notifier).logout();
                                          context.go('/login');
                                        },
                                        child: Text("Ya, Keluar", style: GoogleFonts.inter(fontWeight: FontWeight.w700, color: Colors.white)),
                                      ),
                                    ),
                                  ],
                                )
                              ],
                            ),
                          ),
                        ),
                      );
                    },
                    child: Container(
                      width: 44, height: 44,
                      decoration: BoxDecoration(color: Colors.white.withOpacity(0.2), shape: BoxShape.circle),
                      child: const Icon(Icons.logout, color: Colors.white, size: 20),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 20),
              // Top KPI Row
              Row(
                children: [
                  Expanded(child: _kpiTopCard(Icons.payments_outlined, "Rp${_fmt(activeData['revenue'] ?? 0)}", "Pendapatan")),
                  const SizedBox(width: 12),
                  Expanded(child: _kpiTopCard(Icons.check_circle_outline, activeData['orders']?.toString() ?? "0", "Pesanan Selesai")),
                ],
              ),
            ],
          ),
        ),

        // Period Selection Tabs
        Padding(
          padding: const EdgeInsets.only(top: 24, bottom: 8),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: ['Hari Ini', 'Minggu Ini', 'Bulan Ini'].map((p) {
              bool isSel = _period == p;
              return GestureDetector(
                onTap: () => setState(() => _period = p),
                child: Container(
                  margin: const EdgeInsets.symmetric(horizontal: 5),
                  padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                  decoration: BoxDecoration(
                    color: isSel ? const Color(0xFFF5A623) : Colors.white,
                    borderRadius: BorderRadius.circular(50),
                    boxShadow: [if (!isSel) BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 4)],
                  ),
                  child: Text(
                    p,
                    style: GoogleFonts.inter(color: isSel ? Colors.white : Colors.grey[600], fontWeight: FontWeight.w700, fontSize: 13),
                  ),
                ),
              );
            }).toList(),
          ),
        ),

        // Main Analytics Scroll Area
        Expanded(
          child: ListView(
            padding: const EdgeInsets.fromLTRB(16, 0, 16, 120),
            children: [
              // REVENUE CHART CARD
              Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(30),
                  boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 15)],
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text("Grafik Pendapatan", style: GoogleFonts.inter(fontSize: 16, fontWeight: FontWeight.w800)),
                        Text(chartSubtitle, style: GoogleFonts.inter(fontSize: 10, color: Colors.grey)),
                      ],
                    ),
                    const SizedBox(height: 20),
                    
                    finalChartData.isNotEmpty ? Builder(
                      builder: (ctx) {
                        double maxTotal = 0;
                        for (var p in finalChartData) {
                          double t = double.tryParse(p['total'].toString()) ?? 0;
                          if (t > maxTotal) maxTotal = t;
                        }
                        return SizedBox(
                          height: 120,
                          child: Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            crossAxisAlignment: CrossAxisAlignment.end,
                            children: finalChartData.map<Widget>((point) {
                              double totalRaw = double.tryParse(point['total'].toString()) ?? 0;
                              double pct = maxTotal > 0 ? (totalRaw / maxTotal) : 0;
                              return _buildBar(_fmt(totalRaw), pct, point['date'].toString());
                            }).toList(),
                          ),
                        );
                      }
                    ) : Center(child: Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: Text(
                        _period == 'Hari Ini' ? "Tidak ada grafik untuk format Hari Ini" : "Data grafik belum tersedia",
                        style: GoogleFonts.inter(color: Colors.grey)
                      ),
                    )),
                  ],
                ),
              ),
              const SizedBox(height: 16),

              // RINGKASAN PEMBAYARAN
              Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  color: AppColors.primary,
                  borderRadius: BorderRadius.circular(30),
                  boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 15)],
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        const Icon(Icons.receipt, color: Colors.white70, size: 16),
                        const SizedBox(width: 8),
                        Text("RINGKASAN PEMBAYARAN", style: GoogleFonts.inter(color: Colors.white, fontSize: 13, fontWeight: FontWeight.w800, letterSpacing: 0.5)),
                      ],
                    ),
                    const SizedBox(height: 20),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text("COD", style: GoogleFonts.inter(color: Colors.white, fontWeight: FontWeight.w600, fontSize: 14)),
                        Text(_fmtFull(activeCod), style: GoogleFonts.inter(color: Colors.white, fontWeight: FontWeight.w800, fontSize: 14)),
                      ],
                    ),
                    const SizedBox(height: 12),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text("QRIS", style: GoogleFonts.inter(color: Colors.white, fontWeight: FontWeight.w600, fontSize: 14)),
                        Text(_fmtFull(activeQris), style: GoogleFonts.inter(color: Colors.white, fontWeight: FontWeight.w800, fontSize: 14)),
                      ],
                    ),
                    Padding(
                      padding: const EdgeInsets.symmetric(vertical: 14),
                      child: Divider(color: Colors.white.withOpacity(0.2)),
                    ),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text("Total Pendapatan", style: GoogleFonts.inter(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 16)),
                        Text("Rp${_fmt(activeData['revenue'] ?? 0)}", style: GoogleFonts.inter(color: Colors.white, fontWeight: FontWeight.w900, fontSize: 16)),
                      ],
                    ),
                  ],
                ),
              ),
              const SizedBox(height: 16),

              // MENU TERLARIS
              Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(30)),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        const Icon(Icons.emoji_events, color: Colors.orange, size: 18),
                        const SizedBox(width: 8),
                        Text("Menu Terlaris", style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 14)),
                      ],
                    ),
                    const SizedBox(height: 16),
                    if (topProducts.isEmpty) Text("Belum ada data penjualan", style: GoogleFonts.inter(color: Colors.grey)),
                    ...topProducts.map<Widget>((p) {
                       return Padding(
                         padding: const EdgeInsets.only(bottom: 12),
                         child: Row(
                           mainAxisAlignment: MainAxisAlignment.spaceBetween,
                           children: [
                             Text(p['name'], style: GoogleFonts.inter(color: const Color(0xFF1A1A1A), fontWeight: FontWeight.w600)),
                             Container(
                               padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                               decoration: BoxDecoration(color: const Color(0xFFF8EFDE), borderRadius: BorderRadius.circular(8)),
                               child: Text("Terjual ${p['total_qty']}", style: GoogleFonts.inter(color: AppColors.primary, fontSize: 11, fontWeight: FontWeight.w700)),
                             ),
                           ],
                         ),
                       );
                    }).toList(),
                  ],
                ),
              ),
              const SizedBox(height: 16),

              // RINCIAN TRANSAKSI
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text("Rincian Transaksi", style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 15)),
                  ElevatedButton.icon(
                    onPressed: () async {
                      if (activeTransactions.isEmpty) {
                        ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("Belum ada transaksi untuk di-export")));
                        return;
                      }

                      try {
                        DateTime? earliestDate;
                        DateTime? latestDate;
                        double grandTotal = 0;

                        for (var order in activeTransactions) {
                          final dateStr = order['created_at'] ?? DateTime.now().toIso8601String();
                          final current = DateTime.parse(dateStr).toLocal();
                          if (earliestDate == null || current.isBefore(earliestDate)) earliestDate = current;
                          if (latestDate == null || current.isAfter(latestDate)) latestDate = current;
                          grandTotal += double.tryParse(order['total_amount'].toString()) ?? 0;
                        }

                        String periodStr = "-";
                        if (earliestDate != null && latestDate != null) {
                          if (earliestDate.year == latestDate.year && earliestDate.month == latestDate.month && earliestDate.day == latestDate.day) {
                            periodStr = DateFormat('dd MMMM yyyy').format(earliestDate);
                          } else {
                            periodStr = "${DateFormat('dd MMM yyyy').format(earliestDate)} - ${DateFormat('dd MMM yyyy').format(latestDate)}";
                          }
                        }

                        final pdf = pw.Document();

                        pdf.addPage(
                          pw.MultiPage(
                            pageFormat: PdfPageFormat.a4,
                            margin: const pw.EdgeInsets.all(32),
                            build: (pw.Context context) {
                              return [
                                pw.Header(
                                  level: 0,
                                  child: pw.Column(
                                    crossAxisAlignment: pw.CrossAxisAlignment.start,
                                    children: [
                                      pw.Text("Laporan Penjualan (Rincian Transaksi)", style: pw.TextStyle(fontSize: 24, fontWeight: pw.FontWeight.bold)),
                                      pw.Text("Hayo Chicken", style: const pw.TextStyle(fontSize: 16)),
                                      pw.SizedBox(height: 4),
                                      pw.Text("Periode: $periodStr", style: pw.TextStyle(fontSize: 12, fontWeight: pw.FontWeight.bold)),
                                      pw.Text("Dicetak: ${DateFormat('dd MMM yyyy, HH:mm').format(DateTime.now())}", style: const pw.TextStyle(fontSize: 12, color: PdfColors.grey700)),
                                      pw.SizedBox(height: 10),
                                    ]
                                  )
                                ),
                                
                                ...activeTransactions.map((order) {
                                  final items = order['orderItems'] ?? order['order_items'] ?? [];
                                  final tList = items is List ? items : [];
                                  final dateStr = order['created_at'] ?? DateTime.now().toIso8601String();
                                  final date = DateTime.parse(dateStr).toLocal();
                                  final tglJam = DateFormat('dd MMM yyyy, HH:mm').format(date);
                                  final total = _fmtFull(double.tryParse(order['total_amount'].toString()) ?? 0);

                                  return pw.Container(
                                    margin: const pw.EdgeInsets.only(bottom: 20),
                                    padding: const pw.EdgeInsets.all(14),
                                    decoration: pw.BoxDecoration(
                                      border: pw.Border.all(color: PdfColors.grey400, width: 1),
                                      borderRadius: const pw.BorderRadius.all(pw.Radius.circular(8)),
                                    ),
                                    child: pw.Column(
                                      crossAxisAlignment: pw.CrossAxisAlignment.start,
                                      children: [
                                        pw.Row(
                                          mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
                                          children: [
                                            pw.Text("Order #${order['order_number'] ?? '-'}", style: pw.TextStyle(fontWeight: pw.FontWeight.bold, fontSize: 14)),
                                            pw.Text(tglJam, style: const pw.TextStyle(fontSize: 12, color: PdfColors.grey700)),
                                          ]
                                        ),
                                        pw.SizedBox(height: 8),
                                        pw.Text("Pelanggan: ${order['user']?['name'] ?? 'Pelanggan'}"),
                                        pw.SizedBox(height: 8),
                                        
                                        pw.Table(
                                          border: pw.TableBorder.all(color: PdfColors.grey300),
                                          children: [
                                            pw.TableRow(
                                              decoration: const pw.BoxDecoration(color: PdfColors.grey100),
                                              children: [
                                                pw.Padding(padding: const pw.EdgeInsets.all(4), child: pw.Text("Item", style: pw.TextStyle(fontWeight: pw.FontWeight.bold))),
                                                pw.Padding(padding: const pw.EdgeInsets.all(4), child: pw.Text("Qty", style: pw.TextStyle(fontWeight: pw.FontWeight.bold))),
                                                pw.Padding(padding: const pw.EdgeInsets.all(4), child: pw.Text("Harga/Porsi", style: pw.TextStyle(fontWeight: pw.FontWeight.bold))),
                                              ]
                                            ),
                                            ...tList.map((item) {
                                              final price = _fmtFull(double.tryParse(item['price'].toString()) ?? 0);
                                              return pw.TableRow(
                                                children: [
                                                  pw.Padding(padding: const pw.EdgeInsets.all(4), child: pw.Text(item['product_name_snapshot']?.toString() ?? '-')),
                                                  pw.Padding(padding: const pw.EdgeInsets.all(4), child: pw.Text(item['quantity']?.toString() ?? '0')),
                                                  pw.Padding(padding: const pw.EdgeInsets.all(4), child: pw.Text(price)),
                                                ]
                                              );
                                            }).toList(),
                                          ]
                                        ),
                                        
                                        pw.SizedBox(height: 12),
                                        pw.Row(
                                          mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
                                          children: [
                                            pw.Text("Tipe Kas: ${order['payment_method'] ?? 'TUNAI'}", style: const pw.TextStyle(fontSize: 12)),
                                            pw.Text("Total: $total", style: pw.TextStyle(fontWeight: pw.FontWeight.bold, fontSize: 14)),
                                          ]
                                        ),
                                      ]
                                    ),
                                  );
                                }).toList(),
                                
                                pw.SizedBox(height: 16),
                                pw.Container(
                                  padding: const pw.EdgeInsets.all(16),
                                  decoration: pw.BoxDecoration(
                                    color: PdfColors.grey200,
                                    borderRadius: const pw.BorderRadius.all(pw.Radius.circular(12)),
                                  ),
                                  child: pw.Row(
                                    mainAxisAlignment: pw.MainAxisAlignment.spaceBetween,
                                    children: [
                                      pw.Text("TOTAL KESELURUHAN", style: pw.TextStyle(fontSize: 16, fontWeight: pw.FontWeight.bold)),
                                      pw.Text(_fmtFull(grandTotal), style: pw.TextStyle(fontSize: 18, fontWeight: pw.FontWeight.bold, color: PdfColors.green800)),
                                    ]
                                  )
                                ),
                              ];
                            }
                          )
                        );

                        // Save PDF
                        final Uint8List pdfBytes = await pdf.save();

                        Directory? dir;
                        if (Platform.isAndroid) {
                           dir = await getExternalStorageDirectory();
                        } else {
                           dir = await getApplicationDocumentsDirectory();
                        }
                        
                        final path = '${dir?.path ?? (await getApplicationDocumentsDirectory()).path}/HayoChicken_Sales_${DateTime.now().millisecondsSinceEpoch}.pdf';
                        final file = File(path);
                        await file.writeAsBytes(pdfBytes);
                        
                        if (mounted) {
                          await Share.shareXFiles([XFile(path)], text: 'Export Data Penjualan Hayo Chicken');
                          ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("Membuka menu bagikan file..."), duration: const Duration(seconds: 2), backgroundColor: Colors.green));
                        }
                      } catch (e) {
                        if (mounted) {
                          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text("Gagal memproses file:\n$e"), backgroundColor: Colors.red));
                        }
                      }
                    },
                    icon: const Icon(Icons.download, size: 14),
                    label: const Text("Export"),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primary,
                      foregroundColor: Colors.white,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50)),
                      padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 0),
                      minimumSize: const Size(0, 32),
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 12),
              
              if (activeTransactions.isEmpty)
                 const Center(child: Text("Belum ada transaksi", style: TextStyle(color: Colors.grey))),

              ...activeTransactions.take(15).map((order) {
                 final status = order['status'] ?? '';
                 final items = order['orderItems'] ?? order['order_items'] ?? [];
                 final tList = items is List ? items : [];
                 final dateStr = order['created_at'] ?? DateTime.now().toIso8601String();
                 final date = DateTime.parse(dateStr).toLocal();
                 final timeStr = DateFormat('HH:mm').format(date);
                 final total = _fmtFull(double.tryParse(order['total_amount'].toString()) ?? 0);

                 return Container(
                   margin: const EdgeInsets.only(bottom: 12),
                   padding: const EdgeInsets.all(20),
                   decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24)),
                   child: Column(
                     crossAxisAlignment: CrossAxisAlignment.start,
                     children: [
                       Row(
                         mainAxisAlignment: MainAxisAlignment.spaceBetween,
                         children: [
                           Text(order['order_number'] ?? '-', style: GoogleFonts.inter(color: Colors.grey[400], fontSize: 11)),
                           Text(timeStr, style: GoogleFonts.inter(color: Colors.grey[400], fontSize: 11)),
                         ],
                       ),
                       const SizedBox(height: 4),
                       Text(order['user']?['name'] ?? 'Pelanggan', style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 15)),
                       const SizedBox(height: 4),
                       Text(
                         tList.isNotEmpty ? tList.map((e) => "${e['product_name_snapshot']} x${e['quantity']}").join(' · ') : "Item kosong",
                         style: GoogleFonts.inter(color: Colors.grey[600], fontSize: 12),
                       ),
                       const SizedBox(height: 12),
                       Row(
                         mainAxisAlignment: MainAxisAlignment.spaceBetween,
                         children: [
                           Container(
                             padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                             decoration: BoxDecoration(color: const Color(0xFFFFF3E0), borderRadius: BorderRadius.circular(50)),
                             child: Text(order['payment_method'] == 'QRIS_MANUAL' ? 'QRIS' : (order['payment_method'] ?? 'TUNAI'), style: GoogleFonts.inter(color: const Color(0xFFE65100), fontSize: 10, fontWeight: FontWeight.w800)),
                           ),
                           Text(total, style: GoogleFonts.inter(color: AppColors.primary, fontWeight: FontWeight.w900, fontSize: 16)),
                         ],
                       ),
                     ],
                   ),
                 );
              }).toList(),
              const SizedBox(height: 16),

              // REKOMENDASI PENGADAAN STOK
              Container(
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(30)),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      children: [
                        const Icon(Icons.shopping_cart_outlined, color: AppColors.primary, size: 18),
                        const SizedBox(width: 8),
                        Text("REKOMENDASI PENGADAAN STOK", style: GoogleFonts.inter(fontWeight: FontWeight.w900, fontSize: 13)),
                      ],
                    ),
                    const SizedBox(height: 6),
                    Text(
                      "Bahan yang perlu disiapkan berdasarkan pesanan masuk",
                      style: GoogleFonts.inter(color: Colors.grey[500], fontSize: 11),
                    ),
                    const SizedBox(height: 20),
                    if (forecasting.isEmpty)
                      Center(
                        child: Padding(
                          padding: const EdgeInsets.symmetric(vertical: 12),
                          child: Column(
                            children: [
                              Icon(Icons.inventory_2_outlined, color: Colors.grey[300], size: 40),
                              const SizedBox(height: 8),
                              Text("Belum ada pesanan masuk", style: GoogleFonts.inter(color: Colors.grey[400], fontSize: 13)),
                              Text("Bahan akan muncul saat ada pesanan baru", style: GoogleFonts.inter(color: Colors.grey[300], fontSize: 11)),
                            ],
                          ),
                        ),
                      ),

                    if (forecasting.isNotEmpty) ...[
                      // Group by priority: estimate >= 5 = urgent, >=2 = medium, rest = normal
                      Builder(builder: (ctx) {
                        final urgent = forecasting.where((f) => (f['estimate'] as num) >= 5).toList();
                        final medium = forecasting.where((f) => (f['estimate'] as num) >= 2 && (f['estimate'] as num) < 5).toList();
                        final normal = forecasting.where((f) => (f['estimate'] as num) < 2).toList();

                        Widget chipGroup(String label, Color labelColor, Color chipColor, Color textColor, List items) {
                          if (items.isEmpty) return const SizedBox.shrink();
                          return Padding(
                            padding: const EdgeInsets.only(bottom: 16),
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Row(
                                  children: [
                                    Container(width: 8, height: 8, decoration: BoxDecoration(color: labelColor, shape: BoxShape.circle)),
                                    const SizedBox(width: 6),
                                    Text(label, style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 11, color: labelColor)),
                                  ],
                                ),
                                const SizedBox(height: 10),
                                Wrap(
                                  spacing: 8,
                                  runSpacing: 8,
                                  children: items.map<Widget>((f) {
                                    return Container(
                                      padding: const EdgeInsets.symmetric(horizontal: 14, vertical: 7),
                                      decoration: BoxDecoration(
                                        color: chipColor,
                                        borderRadius: BorderRadius.circular(20),
                                      ),
                                      child: Text(
                                        f['material'],
                                        style: GoogleFonts.inter(color: textColor, fontWeight: FontWeight.w700, fontSize: 12),
                                      ),
                                    );
                                  }).toList(),
                                ),
                              ],
                            ),
                          );
                        }

                        return Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          children: [
                            chipGroup("🔴  Segera Siapkan", Colors.red[700]!, Colors.red[50]!, Colors.red[700]!, urgent),
                            chipGroup("🟠  Perlu Disiapkan", Colors.orange[700]!, Colors.orange[50]!, Colors.orange[700]!, medium),
                            chipGroup("🟢  Pantau Ketersediaan", Colors.green[700]!, Colors.green[50]!, Colors.green[700]!, normal),
                          ],
                        );
                      }),
                    ],
                  ],
                ),
              ),

            ],
          ),
        ),
      ],
    );
  }

  Widget _kpiTopCard(IconData icon, String value, String label) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white.withOpacity(0.12),
        borderRadius: BorderRadius.circular(22),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(icon, color: Colors.white, size: 18),
          const SizedBox(height: 4),
          Text(value, style: GoogleFonts.inter(color: Colors.white, fontSize: 20, fontWeight: FontWeight.w900)),
          Text(label, style: GoogleFonts.inter(color: Colors.white70, fontSize: 10, fontWeight: FontWeight.w500)),
        ],
      ),
    );
  }

  Widget _buildBar(String val, double heightPct, String day) {
    bool isZero = val == "0" || val == "Rp0";
    return Column(
      mainAxisAlignment: MainAxisAlignment.end,
      children: [
        Text(val, style: TextStyle(fontSize: 8, color: isZero ? Colors.grey[400] : Colors.grey)),
        const SizedBox(height: 4),
        Container(
          width: 24,
          height: isZero ? 4 : 80 * (heightPct > 1.0 ? 1.0 : (heightPct < 0.1 ? 0.1 : heightPct)),
          decoration: BoxDecoration(
            color: isZero ? Colors.grey[300] : AppColors.primary, 
            borderRadius: BorderRadius.circular(isZero ? 2 : 6)
          ),
        ),
        const SizedBox(height: 6),
        Text(day, style: const TextStyle(fontSize: 9, color: Colors.grey, fontWeight: FontWeight.bold)),
      ],
    );
  }
}
