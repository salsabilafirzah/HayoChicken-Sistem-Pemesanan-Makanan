import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../auth/providers/auth_provider.dart';
import '../../catalog/providers/product_provider.dart';
import '../../../core/theme/app_theme.dart';

class SellerProductListScreen extends ConsumerWidget {
  const SellerProductListScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final state = ref.watch(productProvider);

    return Scaffold(
      backgroundColor: const Color(0xFFF9F4EB),
      body: Stack(
        children: [
          Column(
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
                            Text("Manajemen Menu", style: GoogleFonts.inter(color: Colors.white70, fontSize: 12)),
                            Text("Hayo Chicken", style: GoogleFonts.inter(color: Colors.white, fontSize: 26, fontWeight: FontWeight.w900, letterSpacing: -0.5)),
                          ],
                        ),
                        GestureDetector(
                          onTap: () {
                            ref.read(authProvider.notifier).logout();
                            // Handle navigation manually to ensure clean state
                            while(Navigator.canPop(context)) { Navigator.pop(context); }
                            GoRouter.of(context).go('/login');
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
                    GridView.count(
                      shrinkWrap: true,
                      physics: const NeverScrollableScrollPhysics(),
                      crossAxisCount: 2,
                      childAspectRatio: 1.8,
                      crossAxisSpacing: 12,
                      mainAxisSpacing: 12,
                      children: [
                         _kpiCard(Icons.fastfood_outlined, "${state.products.length}", "Total Produk"),
                         _kpiCard(Icons.category_outlined, "${state.categories.length}", "Kategori"),
                         _kpiCard(Icons.inventory_2_outlined, "Ready", "Status Stok"),
                         _kpiCard(Icons.star_outline, "4.8", "Rating Produk"),
                      ],
                    ),
                  ],
                ),
              ),

              // Product List
              Expanded(
                child: ListView.builder(
                  padding: const EdgeInsets.fromLTRB(16, 24, 16, 120),
                  itemCount: state.products.length,
                  itemBuilder: (context, index) {
                    final product = state.products[index];
                    return Container(
                      margin: const EdgeInsets.only(bottom: 16),
                      padding: const EdgeInsets.all(20),
                      decoration: BoxDecoration(
                        color: Colors.white,
                        borderRadius: BorderRadius.circular(30),
                        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 15, offset: const Offset(0, 8))],
                      ),
                      child: Row(
                        children: [
                          Container(
                            width: 70, height: 70,
                            decoration: BoxDecoration(color: const Color(0xFFF5F5F5), borderRadius: BorderRadius.circular(18)),
                            child: const Icon(Icons.restaurant, color: Color(0xFFD32F2F), size: 34),
                          ),
                          const SizedBox(width: 18),
                          Expanded(
                            child: Column(
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text(product.name, style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 17, color: const Color(0xFF1A1A1A))),
                                Text("Rp ${product.basePrice}", style: GoogleFonts.inter(color: AppColors.primary, fontWeight: FontWeight.w900, fontSize: 16)),
                                Text("Tersedia", style: GoogleFonts.inter(color: Colors.grey[500], fontSize: 12, fontWeight: FontWeight.w600)),
                              ],
                            ),
                          ),
                          OutlinedButton(
                            onPressed: () {},
                            style: OutlinedButton.styleFrom(
                              foregroundColor: AppColors.primary,
                              side: const BorderSide(color: AppColors.primary, width: 2),
                              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50)),
                              minimumSize: const Size(80, 40),
                            ),
                            child: Text("Edit", style: GoogleFonts.inter(fontWeight: FontWeight.bold)),
                          ),
                        ],
                      ),
                    );
                  },
                ),
              ),
            ],
          ),
          
          Positioned(
            bottom: 120,
            right: 30,
            child: FloatingActionButton(
              onPressed: () {},
              backgroundColor: const Color(0xFFF5A623),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
              elevation: 8,
              child: const Icon(Icons.add, color: Colors.white, size: 30),
            ),
          ),
        ],
      ),
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
          Text(value, style: GoogleFonts.inter(color: Colors.white, fontSize: 18, fontWeight: FontWeight.w900)),
          Text(label, style: GoogleFonts.inter(color: Colors.white70, fontSize: 10, fontWeight: FontWeight.w500)),
        ],
      ),
    );
  }
}
