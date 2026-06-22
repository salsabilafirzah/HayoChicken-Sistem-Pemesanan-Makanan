import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../auth/providers/auth_provider.dart';
import '../providers/seller_order_provider.dart';
import '../services/seller_order_service.dart';
import '../../../core/theme/app_theme.dart';
import '../../../core/network/api_service.dart';
import '../../../core/constants/constants.dart';
import 'package:intl/intl.dart';

class SellerOrderListScreen extends ConsumerStatefulWidget {
  const SellerOrderListScreen({super.key});

  @override
  ConsumerState<SellerOrderListScreen> createState() => _SellerOrderListScreenState();
}

class _SellerOrderListScreenState extends ConsumerState<SellerOrderListScreen> {
  final ApiService _api = ApiService();
  Map<String, dynamic>? _analytics;

  @override
  void initState() {
    super.initState();
    _fetchAnalytics();
  }

  Future<void> _fetchAnalytics() async {
    try {
      final res = await _api.get('/seller/analytics/summary');
      if (mounted) {
        setState(() {
          _analytics = res.data;
        });
      }
    } catch (e) {
      // safe fallback
    }
  }

  String _formatK(int val) {
    if (val >= 1000000) return '${(val / 1000000).toStringAsFixed(1)}jt';
    if (val >= 1000) return '${(val / 1000).toStringAsFixed(0)}k';
    return val.toString();
  }

  @override
  Widget build(BuildContext context) {
    // 1. We map to the existing provider
    final orderState = ref.watch(sellerOrderProvider as ProviderListenable<dynamic>); // Ensure dynamic casting fits definition. Wait, seller_order_provider definition is `final sellerOrderProvider = StateNotifierProvider<SellerOrderNotifier, SellerOrderState>`. We will use exact import.
    // Wait, let's fix imports first! We just imported provider. 
    return Column(
      children: [
        // FIXED HEADER HEIGHT (Match Laravel 100%)
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
                      Text("Dashboard Penjual", style: GoogleFonts.inter(color: Colors.white70, fontSize: 13)),
                      Text("Hayo Chicken", style: GoogleFonts.inter(color: Colors.white, fontSize: 26, fontWeight: FontWeight.w900, letterSpacing: -0.5)),
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
              // Compact KPI Row
              Row(
                children: [
                  Expanded(child: _kpiCard(Icons.shopping_bag_outlined, _analytics?['today']?['orders']?.toString() ?? "0", "Pesanan Hari Ini")),
                  const SizedBox(width: 12),
                  Expanded(child: _kpiCard(Icons.notifications_active_outlined, _analytics?['today']?['new_orders']?.toString() ?? "0", "Pesanan Baru")),
                ],
              ),
            ],
          ),
        ),

        // Status Tabs (Pill Style)
        Padding(
          padding: const EdgeInsets.only(top: 16, bottom: 8),
          child: SingleChildScrollView(
            scrollDirection: Axis.horizontal,
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: Row(
              children: [
                {'v': 'ALL', 'l': 'Semua'},
                {'v': 'NEW', 'l': 'Baru'},
                {'v': 'PENDING_VERIFICATION', 'l': 'Verif. QRIS'},
                {'v': 'PROCESSING', 'l': 'Diproses'},
                {'v': 'DELIVERING', 'l': 'Dikirim'},
                {'v': 'DONE', 'l': 'Selesai'},
                {'v': 'REJECTED', 'l': 'Ditolak'},
              ].map((tabConfig) {
                String val = tabConfig['v']!;
                String label = tabConfig['l']!;
                
                // Read current filter from provider state dynamically
                final currentState = ref.watch(sellerOrderProvider) as dynamic;
                final String currentFilter = currentState.currentFilter ?? 'ALL';
                bool isSel = currentFilter == val;
                
                return GestureDetector(
                  onTap: () {
                    final notifier = ref.read(sellerOrderProvider.notifier) as dynamic;
                    notifier.setFilter(val);
                  },
                  child: Container(
                    margin: const EdgeInsets.only(right: 8),
                    padding: const EdgeInsets.symmetric(horizontal: 22, vertical: 12),
                    decoration: BoxDecoration(
                      color: isSel ? const Color(0xFFF5A623) : Colors.white,
                      borderRadius: BorderRadius.circular(50),
                      boxShadow: [if(!isSel) BoxShadow(color: Colors.black.withOpacity(0.02), blurRadius: 4)],
                    ),
                    child: Text(
                      label,
                      style: GoogleFonts.inter(color: isSel ? Colors.white : Colors.grey[600], fontWeight: FontWeight.bold, fontSize: 13),
                    ),
                  ),
                );
              }).toList(),
            ),
          ),
        ),

        // Dynamic Order List
        Expanded(
          child: () {
            if (orderState.isLoading == true) {
              return const Center(child: CircularProgressIndicator(color: AppColors.primary));
            }
            final List<dynamic> orders = orderState.orders ?? [];
            if (orders.isEmpty) {
               return SingleChildScrollView(
                 padding: const EdgeInsets.only(top: 80),
                 child: Column(
                   mainAxisAlignment: MainAxisAlignment.center,
                   children: [
                     const Icon(Icons.inbox_outlined, size: 60, color: Colors.grey),
                     const SizedBox(height: 12),
                     Text("Tidak ada pesanan", style: GoogleFonts.inter(color: Colors.black54, fontSize: 16, fontWeight: FontWeight.w600)),
                     if (orderState.errorMessage != null) ...[
                       const SizedBox(height: 12),
                       Padding(
                         padding: const EdgeInsets.symmetric(horizontal: 24),
                         child: Text("ERROR DETECTED:\n${orderState.errorMessage}", style: GoogleFonts.inter(color: Colors.red, fontSize: 12, fontWeight: FontWeight.bold), textAlign: TextAlign.center),
                       )
                     ]
                   ],
                 )
               );
            }

            return ListView.builder(
              padding: const EdgeInsets.only(left: 16, right: 16, bottom: 120),
              itemCount: orders.length,
              itemBuilder: (context, index) {
                return _buildOrderCard(orders[index]);
              },
            );
          }(),
        ),
      ],
    );
  }

  Widget _kpiCard(IconData icon, String value, String label) {
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

  Widget _statusBadge(String rawStatus) {
    Map<String, dynamic> spec = {
      'NEW': {'c': const Color(0xFFFFF3E0), 't': const Color(0xFFE65100), 'lbl': 'Baru'},
      'PENDING_VERIFICATION': {'c': const Color(0xFFE3F2FD), 't': const Color(0xFF1565C0), 'lbl': 'Verifikasi QRIS'},
      'PROCESSING': {'c': const Color(0xFFFBE9E7), 't': const Color(0xFFD84315), 'lbl': 'Diproses'},
      'DELIVERING': {'c': const Color(0xFFE8EAF6), 't': const Color(0xFF283593), 'lbl': 'Dikirim'},
      'DONE': {'c': const Color(0xFFE8F5E9), 't': const Color(0xFF2E7D32), 'lbl': 'Selesai'},
      'REJECTED': {'c': const Color(0xFFECEFF1), 't': const Color(0xFF455A64), 'lbl': 'Ditolak'},
    };
    final cfg = spec[rawStatus] ?? {'c': Colors.grey[200], 't': Colors.grey[800], 'lbl': rawStatus};
    
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(color: cfg['c'], borderRadius: BorderRadius.circular(50)),
      child: Text(cfg['lbl'], style: GoogleFonts.inter(color: cfg['t'], fontSize: 10, fontWeight: FontWeight.w900)),
    );
  }

  Widget _buildOrderCard(dynamic order) {
    final status = order['status'] ?? 'NEW';
    final formatter = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp', decimalDigits: 0);
    double rawTotal = 0;
    if (order['total_amount'] != null) rawTotal = double.tryParse(order['total_amount'].toString()) ?? 0;
    final total = formatter.format(rawTotal);

    final dateStr = order['created_at'] ?? DateTime.now().toIso8601String();
    final date = DateTime.parse(dateStr).toLocal();
    final timeStr = DateFormat('HH:mm').format(date);
    
    final itemsPayload = order['orderItems'] ?? order['order_items'] ?? [];
    final itemsList = itemsPayload is List ? itemsPayload : [];
    
    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 10, offset: const Offset(0, 4))],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          // Header Row: Order Number & Badge
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              Flexible(child: Text("#${order['order_number'] ?? '000'} • $timeStr", style: GoogleFonts.inter(color: Colors.grey[400], fontSize: 11, fontWeight: FontWeight.w600), maxLines: 1, overflow: TextOverflow.ellipsis)),
              const SizedBox(width: 8),
              _statusBadge(status),
            ],
          ),
          const SizedBox(height: 14),
          
          // Customer Name
          Text(order['user']?['name'] ?? "Pelanggan", style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 16, color: const Color(0xFF1F1F1F))),
          const SizedBox(height: 8),
          
          // Items & Location
          Text(
            itemsList.isNotEmpty 
                ? itemsList.map((e) => "${e['product_name_snapshot'] ?? '?'} x${e['quantity'] ?? '1'}").join(' · ')
                : "Tidak ada detail item",
            style: GoogleFonts.inter(color: Colors.grey[500], fontSize: 12),
          ),
          const SizedBox(height: 6),
          Row(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              const Icon(Icons.location_on_outlined, size: 14, color: AppColors.primary),
              const SizedBox(width: 4),
              Flexible(child: Text(order['delivery_address'] ?? '-', style: GoogleFonts.inter(color: Colors.grey[500], fontSize: 12), maxLines: 1, overflow: TextOverflow.ellipsis)),
            ],
          ),
          const SizedBox(height: 20),
          
          // Bottom Row: Total & Actions
          Row(
            crossAxisAlignment: CrossAxisAlignment.center,
            children: [
              Text(total, style: GoogleFonts.inter(color: AppColors.primary, fontWeight: FontWeight.w900, fontSize: 16)),
              const SizedBox(width: 12),
              Expanded(child: _buildActions(order)),
            ],
          ),
        ],
      ),
    );
  }

  Widget _buildActions(dynamic order) {
    final status = order['status'];
    final orderId = order['id'];
    
    void update(String toStatus, {String? note}) async {
       final notifier = ref.read(sellerOrderProvider.notifier) as dynamic;
       final res = await notifier.updateStatus(orderId, toStatus, note: note);
       if (mounted) {
         if (res['success'] == true) {
           ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("Berhasil memperbarui pesanan!"), backgroundColor: Colors.green));
         } else {
           ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(res['message'] ?? 'Gagal memperbarui'), backgroundColor: Colors.red));
         }
       }
    }
    
    void showConfirmationDialog({
      required Widget iconWidget,
      required Color iconBgColor,
      required String title,
      required String subtitle,
      required String confirmText,
      required Color confirmColor,
      required VoidCallback onConfirm,
    }) {
       showDialog(
         context: context,
         builder: (ctx) => Dialog(
           shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(30)),
           backgroundColor: Colors.white,
           child: Padding(
             padding: const EdgeInsets.all(24.0),
             child: Column(
               mainAxisSize: MainAxisSize.min,
               children: [
                 Container(
                   width: 70, height: 70,
                   decoration: BoxDecoration(color: iconBgColor, shape: BoxShape.circle),
                   child: Center(child: iconWidget),
                 ),
                 const SizedBox(height: 16),
                 Text(title, style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 18, color: Colors.black)),
                 const SizedBox(height: 8),
                 Text(subtitle, style: GoogleFonts.inter(color: Colors.grey[600], fontSize: 12), textAlign: TextAlign.center),
                 const SizedBox(height: 24),
                 Row(
                   children: [
                     Expanded(
                       child: OutlinedButton(
                         onPressed: () => Navigator.pop(ctx),
                         style: OutlinedButton.styleFrom(foregroundColor: AppColors.primary, side: const BorderSide(color: AppColors.primary, width: 1.5), padding: const EdgeInsets.symmetric(vertical: 14), shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50))),
                         child: const Text("Batal", style: TextStyle(fontWeight: FontWeight.w800, fontSize: 13)),
                       ),
                     ),
                     const SizedBox(width: 12),
                     Expanded(
                       child: ElevatedButton(
                         onPressed: () {
                           Navigator.pop(ctx);
                           onConfirm();
                         },
                         style: ElevatedButton.styleFrom(backgroundColor: confirmColor, foregroundColor: Colors.white, padding: const EdgeInsets.symmetric(vertical: 14), shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50))),
                         child: Text(confirmText, style: const TextStyle(fontWeight: FontWeight.w800, fontSize: 13), maxLines: 1, overflow: TextOverflow.ellipsis),
                       ),
                     ),
                   ],
                 )
               ],
             ),
           ),
         ),
       );
    }
    
    void showQrisModal() {
       showDialog(
         context: context,
         builder: (ctx) => Dialog(
           shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(30)),
           backgroundColor: Colors.white,
           child: Padding(
             padding: const EdgeInsets.all(24.0),
             child: Column(
               mainAxisSize: MainAxisSize.min,
               children: [
                 Container(
                   width: 70, height: 70,
                   decoration: const BoxDecoration(color: Color(0xFFE8F5E9), shape: BoxShape.circle),
                   child: const Center(child: Text("\$", style: TextStyle(color: Colors.green, fontSize: 32, fontWeight: FontWeight.bold))),
                 ),
                 const SizedBox(height: 16),
                 Text("Verifikasi QRIS", style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 18, color: Colors.black)),
                 const SizedBox(height: 8),
                 Text("Silakan periksa bukti pembayaran berikut:", style: GoogleFonts.inter(color: Colors.grey[600], fontSize: 12), textAlign: TextAlign.center),
                 const SizedBox(height: 16),
                 ClipRRect(
                   borderRadius: BorderRadius.circular(16),
                   child: order['payment_receipt'] != null 
                     ? Image.network(
                         "${AppConstants.baseUrl.replaceAll('/api/v1', '')}/storage/${order['payment_receipt']}", 
                         height: 250, 
                         width: double.infinity, 
                         fit: BoxFit.cover, 
                         errorBuilder: (_,__,___) => const Icon(Icons.broken_image, size: 80, color: Colors.grey)
                       )
                     : Container(height: 250, color: Colors.grey[200], child: const Center(child: Icon(Icons.no_photography, size: 80, color: Colors.grey))),
                 ),
                 const SizedBox(height: 24),
                 Row(
                   children: [
                     Expanded(
                       child: OutlinedButton(
                         onPressed: () {
                             Navigator.pop(ctx);
                             update('REJECTED', note: 'Bukti transfer QRIS tidak valid.');
                         },
                         style: OutlinedButton.styleFrom(foregroundColor: AppColors.primary, side: const BorderSide(color: AppColors.primary, width: 1.5), padding: const EdgeInsets.symmetric(vertical: 14), shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50))),
                         child: const Text("Tolak", style: TextStyle(fontWeight: FontWeight.w800, fontSize: 13)),
                       ),
                     ),
                     const SizedBox(width: 12),
                     Expanded(
                       child: ElevatedButton(
                         onPressed: () {
                           Navigator.pop(ctx);
                           update('PROCESSING');
                         },
                         style: ElevatedButton.styleFrom(backgroundColor: AppColors.primary, foregroundColor: Colors.white, padding: const EdgeInsets.symmetric(vertical: 14), shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50))),
                         child: const Text("Verifikasi & Terima", style: TextStyle(fontWeight: FontWeight.w800, fontSize: 13), maxLines: 1, overflow: TextOverflow.ellipsis),
                       ),
                     ),
                   ],
                 )
               ],
             ),
           ),
         ),
       );
    }

    // Function wrappers for modals
    void runRejectModal() {
      showConfirmationDialog(
        iconWidget: const Icon(Icons.cancel_outlined, color: Colors.red, size: 36),
        iconBgColor: const Color(0xFFFFEBEE),
        title: "Tolak Pesanan?",
        subtitle: "Pesanan akan dibatalkan dan pembeli akan mendapat notifikasi bahwa pesanan ditolak.",
        confirmText: "Ya, Tolak",
        confirmColor: AppColors.primary, 
        onConfirm: () => update('REJECTED', note: 'Pesanan ditolak penjual.'),
      );
    }

    if (status == 'NEW') {
      return Row(
        mainAxisAlignment: MainAxisAlignment.end,
        children: [
          SizedBox(
            height: 38,
            child: OutlinedButton(
              onPressed: runRejectModal,
              style: OutlinedButton.styleFrom(foregroundColor: AppColors.primary, side: const BorderSide(color: AppColors.primary), padding: const EdgeInsets.symmetric(horizontal: 16)),
              child: const Text("Tolak", style: TextStyle(fontWeight: FontWeight.w700, fontSize: 13)),
            ),
          ),
          const SizedBox(width: 8),
          Flexible(
            child: SizedBox(
              height: 38,
              child: ElevatedButton(
                onPressed: () => showConfirmationDialog(
                  iconWidget: const Icon(Icons.check_circle_outline, color: Colors.green, size: 36),
                  iconBgColor: const Color(0xFFE8F5E9),
                  title: "Terima Pesanan?",
                  subtitle: "Pesanan akan segera diproses dan kamu harus segera menyiapkan makanannya.",
                  confirmText: "Ya, Terima",
                  confirmColor: const Color(0xFF990000), 
                  onConfirm: () => update('PROCESSING'),
                ),
                style: ElevatedButton.styleFrom(backgroundColor: AppColors.primary, foregroundColor: Colors.white, padding: const EdgeInsets.symmetric(horizontal: 16)),
                child: const Text("Terima", style: TextStyle(fontWeight: FontWeight.w700, fontSize: 13), maxLines: 1, overflow: TextOverflow.ellipsis),
              ),
            ),
          ),
        ],
      );
    }
    
    if (status == 'PENDING_VERIFICATION') {
      return Row(
        mainAxisAlignment: MainAxisAlignment.end,
        children: [
          SizedBox(
            height: 38,
            child: OutlinedButton(
              onPressed: runRejectModal,
              style: OutlinedButton.styleFrom(foregroundColor: AppColors.primary, side: const BorderSide(color: AppColors.primary, width: 1.5), padding: const EdgeInsets.symmetric(horizontal: 16), shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50))),
              child: const Text("Tolak", style: TextStyle(fontWeight: FontWeight.w700, fontSize: 13)),
            ),
          ),
          const SizedBox(width: 8),
          Flexible(
            child: SizedBox(
              height: 38,
              child: ElevatedButton(
                onPressed: showQrisModal,
                style: ElevatedButton.styleFrom(backgroundColor: AppColors.primary, foregroundColor: Colors.white, padding: const EdgeInsets.symmetric(horizontal: 16), shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50))),
                child: const Text("Verifikasi QRIS", style: TextStyle(fontWeight: FontWeight.w700, fontSize: 13), maxLines: 1, overflow: TextOverflow.ellipsis),
              ),
            ),
          ),
        ],
      );
    }

    if (status == 'PROCESSING') {
      return Row(
        mainAxisAlignment: MainAxisAlignment.end,
        children: [
          Flexible(
            child: SizedBox(
              height: 38,
              child: ElevatedButton(
                onPressed: () => showConfirmationDialog(
                  iconWidget: const Icon(Icons.local_shipping_outlined, color: Colors.green, size: 36),
                  iconBgColor: const Color(0xFFE8F5E9),
                  title: "Kirim Pesanan?",
                  subtitle: "Pesanan akan ditandai sebagai \"Dalam Pengiriman\". Pastikan kamu sudah berangkat!",
                  confirmText: "Ya, Kirim Sekarang",
                  confirmColor: const Color(0xFF990000), 
                  onConfirm: () => update('DELIVERING'),
                ),
                style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFFD84315), foregroundColor: Colors.white, padding: const EdgeInsets.symmetric(horizontal: 16), shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50))),
                child: const Text("Kirim Sekarang", style: TextStyle(fontWeight: FontWeight.w700, fontSize: 13), maxLines: 1, overflow: TextOverflow.ellipsis),
              ),
            ),
          ),
        ],
      );
    }

    if (status == 'DELIVERING') {
      return Row(
        mainAxisAlignment: MainAxisAlignment.end,
        children: [
          Flexible(
            child: SizedBox(
              height: 38,
              child: ElevatedButton(
                onPressed: () => showConfirmationDialog(
                  iconWidget: const Icon(Icons.check_box_outlined, color: Colors.blue, size: 36),
                  iconBgColor: const Color(0xFFE3F2FD),
                  title: "Selesaikan Pesanan?",
                  subtitle: "Pastikan pesanan sudah diterima oleh pembeli dengan baik.",
                  confirmText: "Tandai Selesai",
                  confirmColor: const Color(0xFF283593), 
                  onConfirm: () => update('DONE'),
                ),
                style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFF283593), foregroundColor: Colors.white, padding: const EdgeInsets.symmetric(horizontal: 16), shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50))),
                child: const Text("Tandai Selesai", style: TextStyle(fontWeight: FontWeight.w700, fontSize: 13), maxLines: 1, overflow: TextOverflow.ellipsis),
              ),
            ),
          ),
        ],
      );
    }

    return const SizedBox.shrink();
  }
}

