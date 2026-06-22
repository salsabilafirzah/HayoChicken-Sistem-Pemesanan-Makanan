import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:flutter_slidable/flutter_slidable.dart';
import '../providers/product_provider.dart';
import '../../cart/providers/cart_provider.dart';
import '../../../core/constants/constants.dart';
import '../../../core/theme/app_theme.dart';

class FavoritesScreen extends ConsumerWidget {
  const FavoritesScreen({super.key});

  String _getProductAsset(String name) {
    if (name.contains("Geprek")) return "assets/images/ayam_geprek.png";
    if (name.contains("Crispy")) return "assets/images/fried_chicken.png";
    if (name.contains("Jebew")) return "assets/images/mie_jebew.png";
    if (name.contains("Lemon")) return "assets/images/lemon_tea.png";
    if (name.contains("Rice Bowl")) return "assets/images/rice_bowl.png";
    if (name.contains("Combo")) return "assets/images/paket_combo.png";
    return "assets/images/ayam_geprek.png";
  }

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final state = ref.watch(productProvider);
    final favProducts = state.products.where((p) => state.favoriteIds.contains(p.id)).toList();

    return Scaffold(
      backgroundColor: const Color(0xFFF8EFDE),
      body: Stack(
        children: [
          Column(
            children: [
              Container(
                padding: const EdgeInsets.fromLTRB(20, 60, 20, 24),
                decoration: const BoxDecoration(
                  color: AppColors.primary,
                  borderRadius: BorderRadius.only(bottomLeft: Radius.circular(35), bottomRight: Radius.circular(35)),
                ),
                child: Row(
                  children: [
                    InkWell(
                      onTap: () => context.pop(),
                      borderRadius: BorderRadius.circular(50),
                      child: Container(
                        padding: const EdgeInsets.all(8),
                        decoration: BoxDecoration(color: Colors.white.withOpacity(0.2), shape: BoxShape.circle),
                        child: const Icon(Icons.chevron_left, color: Colors.white),
                      ),
                    ),
                    const SizedBox(width: 16),
                    Text("Favorit", style: GoogleFonts.inter(color: Colors.white, fontSize: 20, fontWeight: FontWeight.w900)),
                  ],
                ),
              ),

              Expanded(
                child: favProducts.isEmpty 
                  ? _buildEmptyState(context)
                  : ListView.builder(
                      padding: const EdgeInsets.fromLTRB(20, 24, 20, 200),
                      itemCount: favProducts.length,
                      itemBuilder: (context, index) {
                        final product = favProducts[index];
                        return Padding(
                          padding: const EdgeInsets.only(bottom: 16),
                          child: Slidable(
                            key: ValueKey(product.id),
                            endActionPane: ActionPane(
                              motion: const ScrollMotion(),
                              extentRatio: 0.22,
                              children: [
                                CustomSlidableAction(
                                  onPressed: (context) {
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
                                                  decoration: const BoxDecoration(color: Color(0xFFFFEBEE), shape: BoxShape.circle),
                                                  child: const Center(child: Icon(Icons.delete_outline, color: Colors.red, size: 36)),
                                                ),
                                                const SizedBox(height: 16),
                                                Text("Hapus Favorit?", style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 18, color: Colors.black)),
                                                const SizedBox(height: 8),
                                                Text("Yakin ingin menghapus menu ini dari daftar favorit?", style: GoogleFonts.inter(color: Colors.grey[600], fontSize: 12), textAlign: TextAlign.center),
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
                                                          ref.read(productProvider.notifier).toggleFavorite(product.id);
                                                        },
                                                        style: ElevatedButton.styleFrom(backgroundColor: const Color(0xFFC62828), foregroundColor: Colors.white, padding: const EdgeInsets.symmetric(vertical: 14), shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50))),
                                                        child: const Text("Hapus", style: TextStyle(fontWeight: FontWeight.w800, fontSize: 13), maxLines: 1, overflow: TextOverflow.ellipsis),
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
                                  backgroundColor: const Color(0xFFC62828),
                                  foregroundColor: Colors.white,
                                  borderRadius: BorderRadius.circular(24),
                                  child: const Icon(Icons.delete_outline, size: 32),
                                ),
                              ],
                            ),
                            child: Material(
                              color: Colors.transparent,
                              child: InkWell(
                                onTap: () => context.push('/product/${product.id}?from=favorites'),
                                borderRadius: BorderRadius.circular(24),
                                child: Container(
                                  padding: const EdgeInsets.all(16),
                                  decoration: BoxDecoration(
                                    color: Colors.white, 
                                    borderRadius: BorderRadius.circular(24),
                                    boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.02), blurRadius: 10)]
                                  ),
                                  child: Row(
                                    children: [
                                  Container(
                                    width: 75, height: 75,
                                    decoration: BoxDecoration(color: const Color(0xFFF5EFE6), borderRadius: BorderRadius.circular(18)),
                                    child: product.imageUrl != null && product.imageUrl!.isNotEmpty
                                        ? Image.network(
                                            AppConstants.baseUrl.replaceAll('/api/v1', '') + product.imageUrl!,
                                            fit: BoxFit.cover,
                                            errorBuilder: (c, e, s) => Image.asset(_getProductAsset(product.name), fit: BoxFit.cover),
                                          )
                                        : Image.asset(_getProductAsset(product.name), fit: BoxFit.cover),
                                  ),
                                  const SizedBox(width: 16),
                                  Expanded(
                                    child: Column(
                                      crossAxisAlignment: CrossAxisAlignment.start,
                                      children: [
                                        Text(product.name, style: GoogleFonts.inter(fontWeight: FontWeight.w900, fontSize: 14)),
                                        Text("Ditambahkan ke Favorit", style: GoogleFonts.inter(color: const Color(0xFFBBAA99), fontSize: 11, fontWeight: FontWeight.w600)),
                                        const SizedBox(height: 6),
                                        Text("Rp${product.basePrice.toString().replaceAllMapped(RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'), (Match m) => '${m[1]}.')}", 
                                          style: GoogleFonts.inter(color: AppColors.primary, fontWeight: FontWeight.w900, fontSize: 16)),
                                      ],
                                    ),
                                  ),
                                  Row(
                                    children: [
                                      // INDIVIDUAL + BUTTON
                                      Material(
                                        color: const Color(0xFF9D272B),
                                        shape: const CircleBorder(),
                                        child: InkWell(
                                          onTap: () {
                                            context.push('/product/${product.id}?from=favorites');
                                          },
                                          customBorder: const CircleBorder(),
                                          child: const Padding(
                                            padding: EdgeInsets.all(8),
                                            child: Icon(Icons.add_shopping_cart, color: Colors.white, size: 20),
                                          ),
                                        ),
                                      ),
                                    ],
                                  ),
                                ],
                              ),
                            ),
                                                          ),
                            ),
                          ),
                        );
                  },
                ),
              ),
            ],
          ),

          // REMOVED GLOBAL STICKY BAR TO PREVENT BULK BYPASSING ADDONS
        ],
      ),
    );
  }

  Widget _buildEmptyState(BuildContext context) {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.favorite_outline_rounded, size: 120, color: AppColors.primary.withOpacity(0.3)),
          const SizedBox(height: 32),
          Text("Favorit Kosong", style: GoogleFonts.inter(fontSize: 22, fontWeight: FontWeight.w900, color: const Color(0xFF1A1A1A))),
          const SizedBox(height: 12),
          Padding(
            padding: const EdgeInsets.symmetric(horizontal: 50),
            child: Text(
              "Belum ada menu yang kamu tandai sebagai favorit.", 
              textAlign: TextAlign.center,
              style: GoogleFonts.inter(color: Colors.grey, fontSize: 13, fontWeight: FontWeight.w500, height: 1.5),
            ),
          ),
          const SizedBox(height: 48),
          SizedBox(
            width: 220, height: 50,
            child: ElevatedButton(
              onPressed: () => context.go('/home'),
              style: ElevatedButton.styleFrom(
                backgroundColor: AppColors.primary,
                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50)),
                elevation: 10, shadowColor: AppColors.primary.withOpacity(0.4),
              ),
              child: Text("Cari Menu Favorit", style: GoogleFonts.inter(color: Colors.white, fontWeight: FontWeight.w800, fontSize: 14)),
            ),
          ),
          const SizedBox(height: 80),
        ],
      ),
    );
  }
}
