import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';

import '../providers/product_provider.dart';
import '../../../core/theme/app_theme.dart';
import '../../../core/constants/constants.dart';

class CategoryScreen extends ConsumerWidget {
  final int categoryId;
  final String categoryName;

  const CategoryScreen({
    super.key, 
    required this.categoryId, 
    required this.categoryName
  });

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final state = ref.watch(productProvider);
    
    final filteredProducts = state.products.where((p) => p.categoryId == categoryId).toList();

    return Scaffold(
      backgroundColor: const Color(0xFFF8EFDE),
      body: Column(
        children: [
          // MAROON HEADER
          Container(
            padding: const EdgeInsets.fromLTRB(24, 60, 24, 30),
            decoration: const BoxDecoration(
              color: AppColors.primary,
              borderRadius: BorderRadius.only(
                bottomLeft: Radius.circular(45), 
                bottomRight: Radius.circular(45)
              ),
            ),
            child: Row(
              children: [
                GestureDetector(
                  onTap: () => context.pop(),
                  child: Container(
                    width: 45, height: 45,
                    decoration: BoxDecoration(
                      color: Colors.white.withOpacity(0.35),
                      shape: BoxShape.circle,
                    ),
                    child: const Icon(Icons.chevron_left, color: Colors.white, size: 28),
                  ),
                ),
                const SizedBox(width: 18),
                Expanded(
                  child: Text(
                    categoryName,
                    style: GoogleFonts.inter(
                      fontSize: 22, 
                      fontWeight: FontWeight.w900, 
                      color: Colors.white
                    ),
                  ),
                ),
              ],
            ),
          ),

          Expanded(
            child: filteredProducts.isEmpty
                ? _buildNotFoundState()
                : _buildGridResults(filteredProducts),
          ),
        ],
      ),
    );
  }

  Widget _buildNotFoundState() {
    return Center(
      child: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Icon(Icons.sentiment_dissatisfied, size: 80, color: AppColors.primary.withOpacity(0.5)),
          const SizedBox(height: 20),
          Text(
            "Menu Tidak Ditemukan",
            style: GoogleFonts.inter(fontSize: 18, fontWeight: FontWeight.w800, color: Colors.black54),
          ),
        ],
      ),
    );
  }

  Widget _buildGridResults(List<dynamic> products) {
    return GridView.builder(
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 24),
      gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2, 
        childAspectRatio: 0.88, 
        crossAxisSpacing: 16, 
        mainAxisSpacing: 16
      ),
      itemCount: products.length,
      itemBuilder: (context, index) {
        return _CategoryProductCard(product: products[index]);
      },
    );
  }
}

class _CategoryProductCard extends ConsumerWidget {
  final dynamic product;
  const _CategoryProductCard({required this.product});

  String _getProductAsset(String name) {
    String n = name.toLowerCase();
    if (n.contains("geprek")) return "assets/images/ayam_geprek.png";
    if (n.contains("crispy")) return "assets/images/fried_chicken.png";
    if (n.contains("jebew")) return "assets/images/mie_jebew.png";
    if (n.contains("lemon")) return "assets/images/lemon_tea.png";
    if (n.contains("rice bowl")) return "assets/images/rice_bowl.png";
    if (n.contains("combo")) return "assets/images/paket_combo.png";
    return "assets/images/ayam_geprek.png";
  }

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final state = ref.watch(productProvider);
    final isFavorite = state.favoriteIds.contains(product.id);

    return Container(
      decoration: BoxDecoration(
        boxShadow: [
          BoxShadow(
            color: Colors.black.withOpacity(0.04), 
            blurRadius: 15, 
            offset: const Offset(0, 8)
          )
        ]
      ),
      child: Material(
        color: const Color(0xFFEFE0C4), 
        borderRadius: BorderRadius.circular(24), 
        clipBehavior: Clip.antiAlias,
        child: InkWell(
          onTap: () => context.push('/product/${product.id}?from=favorites'),
          child: Stack(
            children: [
              // RED WAVE BACKGROUND
              Positioned(
                top: 0, left: 0, right: 0, height: 125,
                child: ClipPath(
                  clipper: _CategoryWaveClipper(),
                  child: Container(
                    decoration: const BoxDecoration(
                      gradient: LinearGradient(
                        colors: [Color(0xFF5F0004), Color(0xFF9D272B)],
                        begin: Alignment.topLeft,
                        end: Alignment.bottomRight,
                      ),
                      borderRadius: BorderRadius.only(
                        topLeft: Radius.circular(24), 
                        topRight: Radius.circular(24)
                      )
                    ),
                  ),
                ),
              ),

              // PRODUCT IMAGE
              Positioned(
                top: 10, left: 0, right: 0, height: 110,
                child: Center(
                  child: Hero(
                    tag: "cat_product_${product.id}",
                    child: product.imageUrl != null && product.imageUrl!.isNotEmpty
                      ? Image.network(
                          AppConstants.baseUrl.replaceAll('/api/v1', '') + product.imageUrl!,
                          fit: BoxFit.contain,
                          errorBuilder: (c, e, s) => Image.asset(_getProductAsset(product.name), fit: BoxFit.contain),
                        )
                      : Image.asset(_getProductAsset(product.name), fit: BoxFit.contain),
                  ),
                ),
              ),

              // TEXT CONTENT & ACTION ROW
              Positioned(
                left: 14, right: 14, bottom: 14,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    FittedBox(
                      fit: BoxFit.scaleDown,
                      alignment: Alignment.centerLeft,
                      child: Text(
                        product.name, 
                        style: GoogleFonts.inter(fontSize: 14, fontWeight: FontWeight.w900)
                      ),
                    ),
                    Text(
                      product.description ?? "", 
                      style: GoogleFonts.inter(fontSize: 10, color: Colors.grey, fontWeight: FontWeight.w600), 
                      maxLines: 1
                    ),
                    const SizedBox(height: 8),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text(
                          "Rp${product.basePrice.toString().replaceAllMapped(RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'), (Match m) => '${m[1]}.')}", 
                          style: GoogleFonts.inter(fontSize: 14, fontWeight: FontWeight.w900, color: AppColors.primary)
                        ),
                        
                        // ACTION BUTTON
                        Material(
                          color: const Color(0xFF9D272B),
                          shape: const CircleBorder(),
                          child: InkWell(
                            onTap: () {
                              context.push('/product/${product.id}?from=favorites');
                            },
                            customBorder: const CircleBorder(),
                            child: const Padding(
                              padding: EdgeInsets.all(6),
                              child: Icon(Icons.add, color: Colors.white, size: 18),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),

              // FAVORITE BUTTON (Top Corner)
              Positioned(
                top: 14, right: 14,
                child: Material(
                  color: Colors.white,
                  shape: const CircleBorder(),
                  elevation: 4,
                  shadowColor: Colors.black26,
                  child: InkWell(
                    onTap: () {
                      ref.read(productProvider.notifier).toggleFavorite(product.id);
                    },
                    customBorder: const CircleBorder(),
                    child: Padding(
                      padding: const EdgeInsets.all(6),
                      child: Icon(
                        isFavorite ? Icons.favorite : Icons.favorite_border, 
                        color: isFavorite ? AppColors.primary : const Color(0xFFBBAA99), 
                        size: 16,
                      ),
                    ),
                  ),
                ),
              ),
            ],
          ),
        ),
      ),
    );
  }
}

class _CategoryWaveClipper extends CustomClipper<Path> {
  @override
  Path getClip(Size size) {
    var path = Path();
    path.lineTo(0, size.height * 0.65);
    
    // Wave smoothing
    var firstControlPoint = Offset(size.width * 0.25, size.height * 0.85);
    var firstEndPoint = Offset(size.width * 0.5, size.height * 0.7);
    path.quadraticBezierTo(firstControlPoint.dx, firstControlPoint.dy, firstEndPoint.dx, firstEndPoint.dy);

    var secondControlPoint = Offset(size.width * 0.75, size.height * 0.55);
    var secondEndPoint = Offset(size.width, size.height * 0.7);
    path.quadraticBezierTo(secondControlPoint.dx, secondControlPoint.dy, secondEndPoint.dx, secondEndPoint.dy);
    
    path.lineTo(size.width, 0);
    path.close();
    return path;
  }
  @override bool shouldReclip(CustomClipper<Path> oldClipper) => false;
}
