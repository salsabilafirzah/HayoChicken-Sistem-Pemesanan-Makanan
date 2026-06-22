import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:go_router/go_router.dart';
import 'package:intl/intl.dart';
import '../../../../core/theme/app_theme.dart';
import '../../orders/providers/order_provider.dart';
import '../../cart/providers/cart_provider.dart';

class OrderHistoryScreen extends ConsumerStatefulWidget {
  const OrderHistoryScreen({super.key});

  @override
  ConsumerState<OrderHistoryScreen> createState() => _OrderHistoryScreenState();
}

class _OrderHistoryScreenState extends ConsumerState<OrderHistoryScreen> {
  String _selectedFilter = 'ALL';

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8EFDE),
      body: Column(
        children: [
          Container(
            padding: const EdgeInsets.fromLTRB(20, 60, 20, 24),
            decoration: const BoxDecoration(
              color: AppColors.primary,
              borderRadius: BorderRadius.only(bottomLeft: Radius.circular(35), bottomRight: Radius.circular(35)),
            ),
            child: Row(
              children: [
                GestureDetector(
                  onTap: () => context.pop(),
                  child: Container(
                    padding: const EdgeInsets.all(8),
                    decoration: BoxDecoration(color: Colors.white.withOpacity(0.2), shape: BoxShape.circle),
                    child: const Icon(Icons.chevron_left, color: Colors.white),
                  ),
                ),
                const SizedBox(width: 16),
                Text("Riwayat Pesanan", style: GoogleFonts.inter(color: Colors.white, fontSize: 20, fontWeight: FontWeight.w900)),
              ],
            ),
          ),
          
          // FILTER PILLS
          Padding(
            padding: const EdgeInsets.only(top: 16, bottom: 8),
            child: SingleChildScrollView(
              scrollDirection: Axis.horizontal,
              padding: const EdgeInsets.symmetric(horizontal: 16),
              child: Row(
                children: [
                  {'v': 'ALL', 'l': 'Semua'},
                  {'v': 'NEW', 'l': 'Baru'},
                  {'v': 'PROCESSING', 'l': 'Diproses'},
                  {'v': 'DELIVERING', 'l': 'Dikirim'},
                  {'v': 'DONE', 'l': 'Selesai'},
                  {'v': 'REJECTED', 'l': 'Dibatalkan'},
                ].map((tabConfig) {
                  String val = tabConfig['v']!;
                  String label = tabConfig['l']!;
                  bool isSel = _selectedFilter == val;
                  
                  return GestureDetector(
                    onTap: () => setState(() => _selectedFilter = val),
                    child: Container(
                      margin: const EdgeInsets.only(right: 8),
                      padding: const EdgeInsets.symmetric(horizontal: 22, vertical: 12),
                      decoration: BoxDecoration(
                        color: isSel ? const Color(0xFFF5A623) : Colors.white,
                        borderRadius: BorderRadius.circular(50),
                        boxShadow: [if (!isSel) BoxShadow(color: Colors.black.withOpacity(0.02), blurRadius: 4)],
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

          Expanded(
            child: ref.watch(allOrdersProvider).when(
              data: (allOrders) {
                // Terapkan filter lokal
                final orders = _selectedFilter == 'ALL' 
                  ? allOrders 
                  : allOrders.where((o) {
                      if (_selectedFilter == 'NEW') return o['status'] == 'NEW' || o['status'] == 'PENDING_VERIFICATION';
                      return o['status'] == _selectedFilter;
                    }).toList();

                if (orders.isEmpty) {
                  return Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(Icons.receipt_long_rounded, size: 64, color: Colors.grey[300]),
                        const SizedBox(height: 16),
                        Text("Belum ada riwayat pesanan", style: GoogleFonts.inter(color: Colors.grey, fontWeight: FontWeight.w600)),
                      ],
                    ),
                  );
                }

                return ListView.builder(
                  padding: const EdgeInsets.all(20),
                  itemCount: orders.length,
                  itemBuilder: (context, index) {
                    final order = orders[index];
                    return InkWell(
                      onTap: () => context.push('/order-status', extra: order['order_number']),
                      child: _buildOrderCard(order, context, ref),
                    );
                  },
                );
              },
              loading: () => const Center(child: CircularProgressIndicator(color: AppColors.primary)),
              error: (err, _) => Center(child: Text("Error: $err")),
            ),
          ),
        ],
      ),
    );
  }

  Color _getStatusColor(String status) {
    switch (status) {
      case 'NEW':
      case 'PENDING_VERIFICATION':
        return const Color(0xFFF39C12);
      case 'PROCESSING':
        return const Color(0xFFE67E22);
      case 'DELIVERING':
        return Colors.blue;
      case 'DONE':
        return const Color(0xFF27AE60);
      case 'REJECTED':
        return Colors.red;
      default:
        return Colors.grey;
    }
  }

  String _getStatusLabel(String status) {
    switch (status) {
      case 'NEW': return 'Pesanan Baru';
      case 'PENDING_VERIFICATION': return 'Verifikasi QRIS';
      case 'PROCESSING': return 'Sedang Dimasak';
      case 'DELIVERING': return 'Dikirim';
      case 'DONE': return 'Selesai';
      case 'REJECTED': return 'Dibatalkan';
      default: return status;
    }
  }

  Widget _buildOrderCard(dynamic order, BuildContext context, WidgetRef ref) {
    final status = order['status'];
    final id = order['order_number'];
    final itemsList = order['order_items'] as List<dynamic>;
    final menu = itemsList.map((i) => "${i['product_name_snapshot']} ×${i['quantity']}").join(", ");
    final price = order['total_amount'].toString();
    final DateTime createdAt = DateTime.parse(order['created_at']).toLocal();
    final date = DateFormat('dd MMM yyyy, HH:mm').format(createdAt);

    final Color statusColor = _getStatusColor(status);
    final String statusLabel = _getStatusLabel(status);
    final formattedPrice = price.replaceAllMapped(RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'), (Match m) => '${m[1]}.');

    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24)),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Text(id, style: GoogleFonts.inter(fontWeight: FontWeight.w900, fontSize: 13, color: const Color(0xFF1A1A1A))),
              Container(
                padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 5),
                decoration: BoxDecoration(color: statusColor.withOpacity(0.1), borderRadius: BorderRadius.circular(50)),
                child: Text(statusLabel, style: GoogleFonts.inter(color: statusColor, fontSize: 9, fontWeight: FontWeight.w800)),
              ),
            ],
          ),
          const SizedBox(height: 10),
          Text(menu, style: GoogleFonts.inter(color: Colors.grey[600], fontSize: 12, fontWeight: FontWeight.w500), maxLines: 1, overflow: TextOverflow.ellipsis),
          const SizedBox(height: 12),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text("Total Belanja", style: GoogleFonts.inter(color: Colors.grey[500], fontSize: 10, fontWeight: FontWeight.w600)),
                  Text("Rp $formattedPrice", style: GoogleFonts.inter(color: const Color(0xFF8B1A1A), fontWeight: FontWeight.w900, fontSize: 15)),
                ],
              ),
              Row(
                children: [
                  Text(date, style: GoogleFonts.inter(color: Colors.grey[400], fontSize: 11, fontWeight: FontWeight.w500)),
                  const SizedBox(width: 12),
                  SizedBox(
                    height: 32,
                    child: OutlinedButton(
                      style: OutlinedButton.styleFrom(
                        foregroundColor: AppColors.primary,
                        side: const BorderSide(color: AppColors.primary, width: 1.5),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50)),
                        padding: const EdgeInsets.symmetric(horizontal: 14),
                      ),
                      onPressed: () async {
                        // Tampilkan indikator loading atau tangani state jika perlu
                        ScaffoldMessenger.of(context).showSnackBar(
                          SnackBar(content: Text('Menambahkan ke keranjang...', style: GoogleFonts.inter()), duration: const Duration(seconds: 1)),
                        );

                        // Iterasi per item
                        for (var item in itemsList) {
                          List<String> parsedExtras = [];
                          if (item['selected_extras_snapshot'] != null) {
                            if (item['selected_extras_snapshot'] is List) {
                              parsedExtras = (item['selected_extras_snapshot'] as List).map((e) => e.toString()).toList();
                            }
                          }

                          await ref.read(cartProvider.notifier).addToCart(
                            productId: item['product_id'],
                            quantity: item['quantity'],
                            extras: parsedExtras,
                            note: item['note'],
                          );
                        }
                        
                        if (context.mounted) {
                          context.push('/cart');
                        }
                      },
                      child: Text("Pesan Lagi", style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 11)),
                    ),
                  ),
                ],
              ),
            ],
          ),
        ],
      ),
    );
  }
}
