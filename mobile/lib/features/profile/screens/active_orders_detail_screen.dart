import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../core/theme/app_theme.dart';

class ActiveOrdersDetailScreen extends StatelessWidget {
  const ActiveOrdersDetailScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF9F4EB),
      body: Column(
        children: [
          // HEADER (Match Image)
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
                Text("Status Pesanan", style: GoogleFonts.inter(color: Colors.white, fontSize: 20, fontWeight: FontWeight.w900)),
              ],
            ),
          ),

          Expanded(
            child: ListView(
              padding: const EdgeInsets.all(24),
              children: [
                // ORDER INFO CARD
                _buildCard(
                  child: Column(
                    children: [
                      _buildInfoRow("ID Pesanan", "#HC-20260610-0001", isBold: true),
                      const SizedBox(height: 12),
                      _buildInfoRow("Metode Bayar", "COD"),
                      const SizedBox(height: 12),
                      _buildInfoRow("Total", "Rp12.000", valueColor: AppColors.primary, isBold: true),
                      const SizedBox(height: 12),
                      Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        children: [
                          Text("Status", style: GoogleFonts.inter(color: Colors.grey, fontSize: 13, fontWeight: FontWeight.w600)),
                          Container(
                            padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 4),
                            decoration: BoxDecoration(color: const Color(0xFFF9F4EB), borderRadius: BorderRadius.circular(50)),
                            child: Text("Pesanan Baru", style: GoogleFonts.inter(color: const Color(0xFFB8860B), fontSize: 11, fontWeight: FontWeight.w800)),
                          ),
                        ],
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 20),

                // TIMELINE CARD
                _buildCard(
                  title: "Riwayat Status",
                  child: Column(
                    children: [
                      _buildTimelineItem(
                        icon: Icons.check_rounded,
                        title: "Pesanan Diterima",
                        subtitle: "Pesanan berhasil dibuat",
                        isCompleted: true,
                        isActive: false,
                        color: AppColors.primary,
                      ),
                      _buildTimelineItem(
                        icon: Icons.access_time_rounded,
                        title: "Sedang Dimasak",
                        subtitle: "Pesanan sedang disiapkan",
                        isCompleted: false,
                        isActive: true,
                        color: const Color(0xFFF1B434),
                      ),
                      _buildTimelineItem(
                        icon: Icons.access_time_rounded,
                        title: "Dalam Pengiriman",
                        subtitle: "Estimasi 15-20 menit",
                        isCompleted: false,
                        isActive: false,
                        color: Colors.grey.shade400,
                      ),
                      _buildTimelineItem(
                        icon: Icons.access_time_rounded,
                        title: "Pesanan Tiba",
                        subtitle: "Selamat menikmati pesananmu!",
                        isCompleted: false,
                        isActive: false,
                        isLast: true,
                        color: Colors.grey.shade400,
                      ),
                    ],
                  ),
                ),
                const SizedBox(height: 20),

                // SUMMARY CARD
                _buildCard(
                  child: Column(
                    children: [
                      _buildInfoRow("Subtotal (1 item)", "Rp12.000"),
                      const SizedBox(height: 14),
                      Text("--------------------------------------------------------------------------------", style: TextStyle(color: Colors.grey.withOpacity(0.3), fontSize: 8), maxLines: 1),
                      const SizedBox(height: 14),
                      _buildInfoRow("Total", "Rp12.000", isBold: true, valueColor: AppColors.primary, fontSize: 16),
                    ],
                  ),
                ),
                const SizedBox(height: 32),

                // HOME BUTTON
                SizedBox(
                  width: double.infinity, height: 60,
                  child: ElevatedButton(
                    onPressed: () => context.go('/home'),
                    style: ElevatedButton.styleFrom(
                      backgroundColor: AppColors.primary,
                      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50)),
                      elevation: 5, shadowColor: AppColors.primary.withOpacity(0.4),
                    ),
                    child: Text("Kembali ke Beranda", style: GoogleFonts.inter(fontSize: 15, fontWeight: FontWeight.w900, color: Colors.white)),
                  ),
                ),
                const SizedBox(height: 40),
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildCard({String? title, required Widget child}) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24), boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.01), blurRadius: 10, offset: const Offset(0, 4))]),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (title != null) ...[
            Text(title, style: GoogleFonts.inter(fontSize: 15, fontWeight: FontWeight.w900, color: const Color(0xFF1A1A1A))),
            const SizedBox(height: 20),
          ],
          child,
        ],
      ),
    );
  }

  Widget _buildInfoRow(String label, String value, {bool isBold = false, Color? valueColor, double fontSize = 13}) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(label, style: GoogleFonts.inter(color: Colors.grey, fontSize: 13, fontWeight: FontWeight.w600)),
        Text(
          value, 
          style: GoogleFonts.inter(
            fontWeight: isBold ? FontWeight.w900 : FontWeight.w700, 
            color: valueColor ?? const Color(0xFF1A1A1A),
            fontSize: fontSize,
          )
        ),
      ],
    );
  }

  Widget _buildTimelineItem({
    required IconData icon, 
    required String title, 
    required String subtitle, 
    required Color color,
    bool isCompleted = false,
    bool isActive = false,
    bool isLast = false,
  }) {
    return Row(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        Column(
          children: [
            Container(
              width: 42, height: 42,
              decoration: BoxDecoration(color: isCompleted || isActive ? color : Colors.grey.shade200, shape: BoxShape.circle),
              child: Icon(icon, color: Colors.white, size: 22),
            ),
            if (!isLast)
              Container(width: 2, height: 40, color: isCompleted ? color : Colors.grey.shade200),
          ],
        ),
        const SizedBox(width: 16),
        Expanded(
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              const SizedBox(height: 8),
              Text(title, style: GoogleFonts.inter(fontSize: 14, fontWeight: FontWeight.w900, color: isCompleted || isActive ? color : Colors.grey.shade400)),
              Text(subtitle, style: GoogleFonts.inter(fontSize: 11, fontWeight: FontWeight.w500, color: Colors.grey.shade500)),
            ],
          ),
        ),
      ],
    );
  }
}
