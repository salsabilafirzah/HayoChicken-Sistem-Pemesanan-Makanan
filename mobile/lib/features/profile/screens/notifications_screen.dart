import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:go_router/go_router.dart';
import 'package:intl/intl.dart';
import '../../../../core/theme/app_theme.dart';
import '../../orders/providers/order_provider.dart';

class NotificationsScreen extends ConsumerWidget {
  const NotificationsScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8EFDE),
      body: Column(
        children: [
          // Header Red Design
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
                Text("Notifikasi", style: GoogleFonts.inter(color: Colors.white, fontSize: 22, fontWeight: FontWeight.w900)),
              ],
            ),
          ),

          Expanded(
            child: ref.watch(allOrdersProvider).when(
              data: (orders) {
                if (orders.isEmpty) {
                  return Center(
                    child: Column(
                      mainAxisAlignment: MainAxisAlignment.center,
                      children: [
                        Icon(Icons.notifications_off_outlined, size: 60, color: Colors.grey[300]),
                        const SizedBox(height: 16),
                        Text("Belum ada notifikasi baru", style: GoogleFonts.inter(color: Colors.grey, fontWeight: FontWeight.w600)),
                      ],
                    ),
                  );
                }

                // Urutkan berdasarkan waktu terbaru (updated_at atau created_at)
                final sortedOrders = List<dynamic>.from(orders)
                  ..sort((a, b) {
                    final tA = DateTime.parse(a['updated_at'] ?? a['created_at']);
                    final tB = DateTime.parse(b['updated_at'] ?? b['created_at']);
                    return tB.compareTo(tA);
                  });

                return ListView.builder(
                  padding: const EdgeInsets.all(24),
                  itemCount: sortedOrders.length,
                  itemBuilder: (context, index) {
                    final order = sortedOrders[index];
                    final dateObj = DateTime.parse(order['updated_at'] ?? order['created_at']).toLocal();
                    final formattedTime = DateFormat('dd MMM yyyy, HH:mm').format(dateObj);
                    
                    // Menentukan detail visual notifikasi berdasarkan status pesanan
                    String title = "Pesanan Diperbarui";
                    String body = "Status pesanan ${order['order_number']} telah diperbarui.";
                    IconData icon = Icons.info_outline;
                    Color iconColor = Colors.blue;
                    
                    switch (order['status']) {
                      case 'NEW':
                        title = "Pesanan Baru Diterima";
                        body = "Pesanan ${order['order_number']} berhasil dibuat. Menunggu konfirmasi toko!";
                        icon = Icons.receipt_long;
                        iconColor = const Color(0xFFF39C12);
                        break;
                      case 'PENDING_VERIFICATION':
                        title = "Menunggu Verifikasi QRIS";
                        body = "Pesanan ${order['order_number']} menunggu admin mengecek bukti transfermu.";
                        icon = Icons.qr_code_scanner;
                        iconColor = const Color(0xFFE67E22);
                        break;
                      case 'PROCESSING':
                        title = "Pesanan Sedang Dimasak";
                        body = "Yay! Dapur lagi nyiapin pesanan ${order['order_number']} pesenanmu bosku.";
                        icon = Icons.outdoor_grill_outlined;
                        iconColor = const Color(0xFFE67E22);
                        break;
                      case 'DELIVERING':
                        title = "Pesanan Di Jalan!";
                        body = "Siap-siap! Pesanan ${order['order_number']} lagi meluncur ke tempatmu.";
                        icon = Icons.local_shipping_outlined;
                        iconColor = Colors.blue;
                        break;
                      case 'DONE':
                        title = "Pesanan Selesai";
                        body = "Pesanan ${order['order_number']} telah selesai. Selamat menikmati!";
                        icon = Icons.check_circle_outline;
                        iconColor = Colors.green;
                        break;
                      case 'REJECTED':
                        title = "Pesanan Dibatalkan";
                        body = "Yah, pesanan ${order['order_number']} dibatalkan sama toko.";
                        icon = Icons.cancel_outlined;
                        iconColor = Colors.red;
                        break;
                    }

                    // Tampilkan item notif
                    return _buildNotificationItem(
                      icon: icon,
                      iconColor: iconColor,
                      title: title,
                      body: body,
                      time: formattedTime,
                      isNew: index < 2, // Highlight 2 pesanan terbaru
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

  Widget _buildSectionHeader(String title) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Text(
        title,
        style: GoogleFonts.inter(fontSize: 13, fontWeight: FontWeight.w900, color: const Color(0xFFBBAA99), letterSpacing: 1),
      ),
    );
  }

  Widget _buildNotificationItem({
    required IconData icon,
    required Color iconColor,
    required String title,
    required String body,
    required String time,
    required bool isNew,
  }) {
    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.all(18),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        border: isNew ? Border.all(color: AppColors.primary.withOpacity(0.1), width: 1.5) : null,
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.02), blurRadius: 10, offset: const Offset(0, 4))],
      ),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Container(
            padding: const EdgeInsets.all(12),
            decoration: BoxDecoration(color: iconColor.withOpacity(0.1), borderRadius: BorderRadius.circular(15)),
            child: Icon(icon, color: iconColor, size: 24),
          ),
          const SizedBox(width: 16),
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Expanded(
                      child: Text(title, style: GoogleFonts.inter(fontWeight: FontWeight.w900, fontSize: 14, color: const Color(0xFF1A1A1A))),
                    ),
                    if (isNew)
                      Container(width: 8, height: 8, decoration: const BoxDecoration(color: AppColors.primary, shape: BoxShape.circle)),
                  ],
                ),
                const SizedBox(height: 4),
                Text(body, style: GoogleFonts.inter(color: Colors.black54, fontSize: 12, height: 1.4, fontWeight: FontWeight.w500)),
                const SizedBox(height: 8),
                Text(time, style: GoogleFonts.inter(color: Colors.grey, fontSize: 11, fontWeight: FontWeight.w600)),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
