import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:go_router/go_router.dart';
import '../../../core/theme/app_theme.dart';

class OrderStatusScreen extends StatelessWidget {
  final String orderId;

  const OrderStatusScreen({super.key, required this.orderId});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF9F4EB),
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
                    child: const Icon(Icons.chevron_left, color: Colors.white, size: 24),
                  ),
                ),
                const SizedBox(width: 16),
                Text("Status Pesanan", style: GoogleFonts.inter(color: Colors.white, fontSize: 20, fontWeight: FontWeight.w900)),
              ],
            ),
          ),

          Expanded(
            child: ListView(
              padding: const EdgeInsets.all(24),
              children: [
                // Info Card
                _buildCard(
                  child: Column(
                    children: [
                      _buildRow("ID Pesanan", orderId, isBold: true),
                      const SizedBox(height: 12),
                      _buildRow("Metode Bayar", "COD"),
                      const SizedBox(height: 12),
                      _buildRow("Total", "Rp48.000", valueColor: AppColors.primary, isBold: true),
                      const SizedBox(height: 12),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text("Status", style: GoogleFonts.inter(color: Colors.grey[600], fontSize: 13, fontWeight: FontWeight.w500)),
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
                            decoration: BoxDecoration(color: const Color(0xFFFFF9EE), borderRadius: BorderRadius.circular(20)),
                            child: Text("Pesanan Baru", style: GoogleFonts.inter(color: const Color(0xFFB8860B), fontSize: 11, fontWeight: FontWeight.w900)),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 20),

                // Status Timeline
                _buildCard(
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text("Riwayat Status", style: GoogleFonts.inter(fontSize: 15, fontWeight: FontWeight.w900)),
                      const SizedBox(height: 24),
                      _buildTimelineItem(
                        icon: Icons.check_rounded,
                        color: AppColors.primary,
                        title: "Pesanan Diterima",
                        subtitle: "Pesanan berhasil dibuat",
                        isPast: true,
                        showLine: true,
                      ),
                      _buildTimelineItem(
                        icon: Icons.access_time_filled_rounded,
                        color: const Color(0xFFFFA500),
                        title: "Sedang Dimasak",
                        subtitle: "Pesanan sedang disiapkan",
                        isPast: true,
                        showLine: true,
                      ),
                      _buildTimelineItem(
                        icon: Icons.info_outline_rounded,
                        color: Colors.grey[300]!,
                        title: "Dalam Pengiriman",
                        subtitle: "Estimasi 15-20 menit",
                        isPast: false,
                        showLine: true,
                      ),
                      _buildTimelineItem(
                        icon: Icons.info_outline_rounded,
                        color: Colors.grey[300]!,
                        title: "Pesanan Tiba",
                        subtitle: "Selamat menikmati pesananmu!",
                        isPast: false,
                        showLine: false,
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 20),

                // Summary Card
                _buildCard(
                  child: Column(
                    children: [
                      _buildRow("Subtotal (3 item)", "Rp48.000"),
                      const SizedBox(height: 16),
                      const Divider(height: 1, color: Color(0xFFEEEEEE)),
                      const SizedBox(height: 16),
                      _buildRow("Total", "Rp48.000", isBold: true, valueColor: const Color(0xFF8B1A1A), fontSize: 16),
                    ],
                  ),
                ),
                const SizedBox(height: 32),

                // Back Button
                SizedBox(
                  width: double.infinity, 
                  height: 60,
                  child: ElevatedButton(
                    onPressed: () => context.go('/home'),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: const Color(0xFF8B1A1A),
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50)),
                      elevation: 0,
                    ),
                    child: Text("Kembali ke Beranda", style: GoogleFonts.inter(fontSize: 16, fontWeight: FontWeight.w800, color: Colors.white)),
                  ),
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildTimelineItem({
    required IconData icon,
    required Color color,
    required String title,
    required String subtitle,
    required bool isPast,
    required bool showLine,
  }) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Column(
          children: [
            Container(
              width: 36,
              height: 36,
              decoration: BoxDecoration(color: color, shape: BoxShape.circle),
              child: Icon(icon, color: Colors.white, size: 20),
            ),
            if (showLine)
              Container(width: 2, height: 40, color: const Color(0xFFEEE5D8)),
          ],
        ),
        const SizedBox(width: 16),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Text(
                title, 
                style: GoogleFonts.inter(
                  fontSize: 14, 
                  fontWeight: FontWeight.w700, 
                  color: isPast ? color : Colors.grey[400]
                )
              ),
              const SizedBox(height: 4),
              Text(
                subtitle, 
                style: GoogleFonts.inter(
                  fontSize: 12, 
                  fontWeight: FontWeight.w500, 
                  color: Colors.grey[500]
                )
              ),
            ],
          ),
        ),
      ],
    );
  }

  Widget _buildCard({required Widget child}) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24)),
      child: child,
    );
  }

  Widget _buildRow(String label, String value, {bool isBold = false, Color? valueColor, double fontSize = 13}) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(label, style: GoogleFonts.inter(color: Colors.grey[600], fontSize: fontSize, fontWeight: FontWeight.w500)),
        Text(value, style: GoogleFonts.inter(color: valueColor ?? const Color(0xFF1A1A1A), fontSize: fontSize, fontWeight: isBold ? FontWeight.w900 : FontWeight.w700)),
      ],
    );
  }
}
