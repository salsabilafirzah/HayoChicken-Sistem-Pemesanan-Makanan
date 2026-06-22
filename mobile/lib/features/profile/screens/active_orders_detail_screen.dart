import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import '../../../core/theme/app_theme.dart';
import '../../orders/providers/order_provider.dart';

class ActiveOrdersDetailScreen extends ConsumerWidget {
  const ActiveOrdersDetailScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8EFDE),
      body: Column(
        children: [
          // HEADER
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
                Text("Pesanan Aktif", style: GoogleFonts.inter(color: Colors.white, fontSize: 20, fontWeight: FontWeight.w900)),
              ],
            ),
          ),

          Expanded(
            child: ref.watch(activeOrdersProvider).when(
              data: (orders) {
                if (orders.isEmpty) {
                  return Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(Icons.local_shipping_outlined, size: 64, color: Colors.grey[300]),
                        const SizedBox(height: 16),
                        Text("Tidak ada pesanan aktif", style: GoogleFonts.inter(color: Colors.grey, fontWeight: FontWeight.w600)),
                      ],
                    ),
                  );
                }

                return ListView.builder(
                  padding: const EdgeInsets.all(20),
                  itemCount: orders.length,
                  itemBuilder: (context, index) {
                    final order = orders[index];
                    final items = order['order_items'] as List<dynamic>;
                    final summaryText = items.map((i) => "${i['product_name_snapshot']} ×${i['quantity']}").join(", ");
                    
                    final DateTime createdAt = DateTime.parse(order['created_at']).toLocal();
                    final String formattedDate = DateFormat('dd MMM yyyy, HH:mm').format(createdAt);
                    
                    return InkWell(
                      onTap: () => context.push('/order-status', extra: order['order_number']),
                      child: _buildOrderCard(
                        order['order_number'], 
                        summaryText, 
                        order['total_amount'].toString(), 
                        formattedDate, 
                        order['status'],
                      ),
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

  Widget _buildOrderCard(String id, String menu, String price, String date, String status) {
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
              Text("Rp $formattedPrice", style: GoogleFonts.inter(color: const Color(0xFF8B1A1A), fontWeight: FontWeight.w900, fontSize: 15)),
              Text(date, style: GoogleFonts.inter(color: Colors.grey[400], fontSize: 11, fontWeight: FontWeight.w500)),
            ],
          ),
        ],
      ),
    );
  }
}
