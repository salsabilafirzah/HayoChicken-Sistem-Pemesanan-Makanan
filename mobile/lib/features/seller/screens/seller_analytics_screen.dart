import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import '../services/analytics_service.dart';
import '../../../core/theme/app_theme.dart';
import '../../auth/providers/auth_provider.dart';

class SellerAnalyticsScreen extends ConsumerStatefulWidget {
  const SellerAnalyticsScreen({super.key});

  @override
  ConsumerState<SellerAnalyticsScreen> createState() => _SellerAnalyticsScreenState();
}

class _SellerAnalyticsScreenState extends ConsumerState<SellerAnalyticsScreen> {
  String _period = 'Hari Ini';

  @override
  Widget build(BuildContext context) {
    return Column(
      children: [
        // FIXED COMPACT HEADER (Match Image 1)
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
                      ref.read(authProvider.notifier).logout();
                      context.go('/login');
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
              // Top KPI Grid
              GridView.count(
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                crossAxisCount: 2,
                childAspectRatio: 1.8,
                crossAxisSpacing: 12,
                mainAxisSpacing: 12,
                children: [
                  _kpiTopCard(Icons.shopping_bag_outlined, "0", "Pesanan Hari Ini"),
                  _kpiTopCard(Icons.payments_outlined, "Rp700k", "Pendapatan"),
                  _kpiTopCard(Icons.notifications_active_outlined, "1", "Pesanan Baru"),
                  _kpiTopCard(Icons.star_outline, "New", "Rating Toko"),
                ],
              ),
            ],
          ),
        ),

        // Period Selection Tabs
        Padding(
          padding: const EdgeInsets.symmetric(vertical: 20),
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
              // GRID 2x2 FOR ANALYTICS CARDS (The "Bedaa" fix)
              GridView.count(
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                crossAxisCount: 2,
                childAspectRatio: 0.9,
                crossAxisSpacing: 12,
                mainAxisSpacing: 12,
                children: [
                  _analyticsGridCard(Icons.attach_money, "Rp700rb", "TOTAL OMZET", "0% vs sebelumnya", const Color(0xFFFFEBEE), Colors.red),
                  _analyticsGridCard(Icons.check_circle_outline, "24", "PESANAN SELESAI", "4 vs sebelumnya", const Color(0xFFE8F5E9), Colors.green),
                  _analyticsGridCard(Icons.receipt_long, "Rp29rb", "RATA-RATA ORDER", "5% vs sebelumnya", const Color(0xFFE3F2FD), Colors.blue),
                  _analyticsGridCard(Icons.payment, "C", "FAVORIT BAYAR", "NaN% dari total", const Color(0xFFFFF3E0), Colors.orange),
                ],
              ),
              const SizedBox(height: 16),
              
              // REVENUE CHART CARD (Grafik Pendapatan)
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
                        Text("Per Jam", style: GoogleFonts.inter(fontSize: 10, color: Colors.grey)),
                      ],
                    ),
                    const SizedBox(height: 20),
                    // Mock Bars matching Image 1
                    SizedBox(
                      height: 120,
                      child: Row(
                        mainAxisAlignment: MainAxisAlignment.spaceBetween,
                        crossAxisAlignment: CrossAxisAlignment.end,
                        children: [
                          _buildBar("108k", 0.7, "03/06"),
                          _buildBar("120k", 0.8, "04/06"),
                          _buildBar("107k", 0.7, "05/06"),
                          _buildBar("54k", 0.4, "06/06"),
                          _buildBar("63k", 0.5, "07/06"),
                          _buildBar("143k", 0.95, "08/06"),
                        ],
                      ),
                    ),
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

  Widget _analyticsGridCard(IconData icon, String value, String label, String trend, Color bgColor, Color iconColor) {
    return Container(
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(28),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.02), blurRadius: 10)],
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceBetween,
            children: [
              Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(color: bgColor, borderRadius: BorderRadius.circular(10)),
                child: Icon(icon, color: iconColor, size: 18),
              ),
              Container(width: 34, height: 34, decoration: BoxDecoration(color: bgColor.withOpacity(0.5), shape: BoxShape.circle)),
            ],
          ),
          const Spacer(),
          Text(value, style: GoogleFonts.inter(fontSize: 20, fontWeight: FontWeight.w900, color: const Color(0xFF1A1A1A))),
          Text(label, style: GoogleFonts.inter(color: Colors.grey[500], fontSize: 9, fontWeight: FontWeight.w700, letterSpacing: 0.3)),
          const SizedBox(height: 6),
          Text("↑ $trend", style: GoogleFonts.inter(color: Colors.green[600], fontSize: 10, fontWeight: FontWeight.w800)),
        ],
      ),
    );
  }

  Widget _buildBar(String val, double heightPct, String day) {
    return Column(
      mainAxisAlignment: MainAxisAlignment.end,
      children: [
        Text(val, style: const TextStyle(fontSize: 8, color: Colors.grey)),
        const SizedBox(height: 4),
        Container(
          width: 44,
          height: 80 * heightPct,
          decoration: BoxDecoration(color: AppColors.primary, borderRadius: BorderRadius.circular(6)),
        ),
        const SizedBox(height: 6),
        Text(day, style: const TextStyle(fontSize: 9, color: Colors.grey, fontWeight: FontWeight.bold)),
      ],
    );
  }
}
