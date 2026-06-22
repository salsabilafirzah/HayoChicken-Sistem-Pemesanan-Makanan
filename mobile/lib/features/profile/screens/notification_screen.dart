import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:go_router/go_router.dart';
import '../../../core/theme/app_theme.dart';

class NotificationScreen extends StatelessWidget {
  const NotificationScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF8EFDE),
      body: Column(
        children: [
          // Header
          Container(
            padding: const EdgeInsets.fromLTRB(20, 60, 20, 24),
            decoration: const BoxDecoration(
              color: AppColors.primary,
              borderRadius: BorderRadius.only(bottomLeft: Radius.circular(35), bottomRight: Radius.circular(35)),
            ),
            child: Row(
              children: [
                GestureDetector(
                  onTap: () => context.go('/home'),
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
            child: ListView(
              padding: const EdgeInsets.all(24),
              children: [
                _buildSectionHeader("TERBARU"),
                _buildNotificationItem(
                  icon: Icons.local_shipping_outlined,
                  iconColor: Colors.orange,
                  title: "Pesanan Sedang Diantar",
                  body: "Pesanan #HC-2024-0042 sedang dalam perjalanan ke alamat kamu.",
                  time: "Baru saja",
                  isNew: true,
                ),
                _buildNotificationItem(
                  icon: Icons.favorite_border,
                  iconColor: Colors.green,
                  title: "Promo Hari Ini!",
                  body: "Diskon 20% untuk semua menu paket. Berlaku sampai jam 21.00 hari ini!",
                  time: "1 jam yang lalu",
                  isNew: true,
                ),
                const SizedBox(height: 24),
                _buildSectionHeader("SEBELUMNYA"),
                _buildNotificationItem(
                  icon: Icons.check_circle_outline,
                  iconColor: Colors.green,
                  title: "Pesanan Berhasil Diterima",
                  body: "Pesanan #HC-2024-0038 telah diterima. Terima kasih sudah memesan di Hayo Chicken!",
                  time: "Kemarin, 13:20",
                  isNew: false,
                ),
                _buildNotificationItem(
                  icon: Icons.info_outline,
                  iconColor: Colors.blue,
                  title: "Menu Baru Tersedia",
                  body: "Coba menu terbaru kami: Ayam Geprek Mozzarella. Rasanya pasti bikin nagih!",
                  time: "3 hari yang lalu",
                  isNew: false,
                ),
              ],
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
        border: isNew ? Border.all(color: AppColors.primary.withOpacity(0.1), width: 1) : null,
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
