import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../auth/providers/auth_provider.dart';
import '../services/seller_order_service.dart';
import '../../../core/theme/app_theme.dart';

class SellerOrderListScreen extends ConsumerStatefulWidget {
  const SellerOrderListScreen({super.key});

  @override
  ConsumerState<SellerOrderListScreen> createState() => _SellerOrderListScreenState();
}

class _SellerOrderListScreenState extends ConsumerState<SellerOrderListScreen> {
  String _selectedStatus = 'ALL';

  @override
  Widget build(BuildContext context) {
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
                      ref.read(authProvider.notifier).logout();
                      Navigator.pushNamedAndRemoveUntil(context, '/login', (route) => false);
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
              // Compact KPI Cards
              GridView.count(
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                crossAxisCount: 2,
                childAspectRatio: 1.8,
                crossAxisSpacing: 12,
                mainAxisSpacing: 12,
                children: [
                  _kpiCard(Icons.shopping_bag_outlined, "0", "Pesanan Hari Ini"),
                  _kpiCard(Icons.payments_outlined, "Rp700k", "Pendapatan"),
                  _kpiCard(Icons.notifications_active_outlined, "1", "Pesanan Baru"),
                  _kpiCard(Icons.star_outline, "New", "Rating Toko"),
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
              children: ['ALL', 'NEW', 'PROCESSING', 'DELIVERING', 'DONE'].map((status) {
                bool isSel = _selectedStatus == status;
                return GestureDetector(
                  onTap: () => setState(() => _selectedStatus = status),
                  child: Container(
                    margin: const EdgeInsets.only(right: 8),
                    padding: const EdgeInsets.symmetric(horizontal: 22, vertical: 12),
                    decoration: BoxDecoration(
                      color: isSel ? const Color(0xFFF5A623) : Colors.white,
                      borderRadius: BorderRadius.circular(50),
                      boxShadow: [if(!isSel) BoxShadow(color: Colors.black.withOpacity(0.02), blurRadius: 4)],
                    ),
                    child: Text(
                      status == 'ALL' ? 'Semua' : (status == 'NEW' ? 'Baru' : status),
                      style: GoogleFonts.inter(color: isSel ? Colors.white : Colors.grey[600], fontWeight: FontWeight.bold, fontSize: 13),
                    ),
                  ),
                );
              }).toList(),
            ),
          ),
        ),

        // Order List (Identical to Laravel Card)
        Expanded(
          child: ListView.builder(
            padding: const EdgeInsets.all(16),
            itemCount: 3,
            itemBuilder: (context, index) {
              return Container(
                margin: const EdgeInsets.only(bottom: 16),
                padding: const EdgeInsets.all(24),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(30),
                  boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 15, offset: const Offset(0, 8))],
                ),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text("#HC-20260608-0009 • 23.20", style: GoogleFonts.inter(color: Colors.grey[400], fontSize: 12)),
                        _statusBadge('DONE'),
                      ],
                    ),
                    const SizedBox(height: 14),
                    Text("Budi Pelanggan", style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 18, color: const Color(0xFF1A1A1A))),
                    const SizedBox(height: 6),
                    Row(
                      children: [
                        const Icon(Icons.location_on, size: 14, color: AppColors.primary),
                        const SizedBox(width: 4),
                        Text("Blater", style: GoogleFonts.inter(color: Colors.grey[500], fontSize: 13)),
                      ],
                    ),
                    const SizedBox(height: 16),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text("Rp12.000", style: GoogleFonts.inter(color: AppColors.primary, fontWeight: FontWeight.w900, fontSize: 20)),
                        ElevatedButton(
                          onPressed: () {},
                          style: ElevatedButton.styleFrom(
                            backgroundColor: const Color(0xFFE8F5E9),
                            foregroundColor: Colors.green,
                            elevation: 0,
                            minimumSize: const Size(100, 40),
                            padding: const EdgeInsets.symmetric(horizontal: 20),
                          ),
                          child: Text("Selesai", style: GoogleFonts.inter(fontWeight: FontWeight.bold)),
                        ),
                      ],
                    ),
                  ],
                ),
              );
            },
          ),
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

  Widget _statusBadge(String status) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 12, vertical: 6),
      decoration: BoxDecoration(color: const Color(0xFFE8F5E9), borderRadius: BorderRadius.circular(50)),
      child: Text("Selesai", style: GoogleFonts.inter(color: Colors.green[700], fontSize: 11, fontWeight: FontWeight.w900)),
    );
  }
}
