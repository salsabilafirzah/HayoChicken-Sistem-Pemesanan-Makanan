import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import '../providers/product_provider.dart';
import '../../cart/providers/cart_provider.dart';
import '../models/product_model.dart';
import '../services/product_service.dart';
import '../../../core/theme/app_theme.dart';

class ProductDetailScreen extends ConsumerStatefulWidget {
  final int productId;
  const ProductDetailScreen({super.key, required this.productId});

  @override
  ConsumerState<ProductDetailScreen> createState() => _ProductDetailScreenState();
}

class _ProductDetailScreenState extends ConsumerState<ProductDetailScreen> {
  int _quantity = 1;
  final Map<int, List<String>> _selectedExtrasPerItem = {0: []};
  final TextEditingController _noteController = TextEditingController();
  ProductModel? _product;
  bool _isInitLoading = true;

  @override
  void initState() {
    super.initState();
    _loadProduct();
  }

  @override
  void dispose() {
    _noteController.dispose();
    super.dispose();
  }

  Future<void> _loadProduct() async {
    final product = await ProductService().getProductDetail(widget.productId);
    if (mounted) {
      setState(() {
        _product = product;
        _isInitLoading = false;
      });
    }
  }

  int get _totalPrice {
    if (_product == null) return 0;
    int extrasTotal = 0;
    _selectedExtrasPerItem.forEach((index, extras) {
      if (index < _quantity) {
        for (var extraName in extras) {
          final extra = _product!.extras.firstWhere((e) => e.name == extraName, 
            orElse: () => _product!.extras.first);
          extrasTotal += extra.additionalPrice;
        }
      }
    });
    return (_product!.basePrice * _quantity) + extrasTotal;
  }

  void _handleAddToCart() async {
    if (_product == null) return;
    
    // Group items by their selected extras list (as string key)
    Map<String, List<String>> uniqueConfigurations = {};
    Map<String, int> configurationCounts = {};
    
    for (int i = 0; i < _quantity; i++) {
       final ex = _selectedExtrasPerItem[i] ?? [];
       // Sort strings to ensure order doesn't create duplicate groups for same items
       final sortedEx = List<String>.from(ex)..sort();
       final key = sortedEx.join(",");
       
       if (!uniqueConfigurations.containsKey(key)) {
         uniqueConfigurations[key] = ex;
         configurationCounts[key] = 1;
       } else {
         configurationCounts[key] = configurationCounts[key]! + 1;
       }
    }

    // Process each unique group as a separate cart addition
    for (var key in uniqueConfigurations.keys) {
      await ref.read(cartProvider.notifier).addToCart(
        productId: _product!.id,
        quantity: configurationCounts[key]!,
        extras: uniqueConfigurations[key]!,
        note: _noteController.text,
      );
    }

    if (mounted) {
      context.push('/cart');
    }
  }

  String _getProductAsset(String name) {
    String n = name.toLowerCase();
    if (n.contains("geprek")) return "assets/images/ayam_geprek.png";
    if (n.contains("crispy")) return "assets/images/fried_chicken.png";
    if (n.contains("jebew")) return "assets/images/mie_jebew.png";
    if (n.contains("lemon")) return "assets/images/lemon_tea.png";
    if (n.contains("rice bowl")) return "assets/images/rice_bowl.png";
    if (n.contains("combo")) return "assets/images/paket_combo.png";
    return "assets/images/fried_chicken.png";
  }

  @override
  Widget build(BuildContext context) {
    if (_isInitLoading) return const Scaffold(backgroundColor: Color(0xFFF9F4EB), body: Center(child: CircularProgressIndicator(color: AppColors.primary)));
    if (_product == null) return const Scaffold(body: Center(child: Text("Produk tidak ditemukan")));

    final productState = ref.watch(productProvider);
    final isFavorite = productState.favoriteIds.contains(_product!.id);

    return Scaffold(
      backgroundColor: const Color(0xFFF9F4EB),
      body: Stack(
        children: [
          SingleChildScrollView(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                _buildHeroHeader(),
                Padding(
                  padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 24),
                  child: Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text(_product!.name, style: GoogleFonts.inter(fontSize: 28, fontWeight: FontWeight.w900, color: const Color(0xFF2D2D2D))),
                      const SizedBox(height: 8),
                      Text(_product!.description ?? "Nikmati ayam goreng renyah bumbu rahasia.", style: GoogleFonts.inter(fontSize: 14, color: Colors.black45, fontWeight: FontWeight.w500, height: 1.5)),
                      const SizedBox(height: 24),
                      Text("Rp${_product!.basePrice.toString().replaceAllMapped(RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'), (Match m) => '${m[1]}.')}",
                        style: GoogleFonts.inter(fontSize: 24, fontWeight: FontWeight.w900, color: AppColors.primary)),
                      const SizedBox(height: 24),
                      Row(
                        children: [
                          _buildQtyButton(Icons.remove, () {
                            if (_quantity > 1) setState(() => _quantity--);
                          }),
                          Padding(
                            padding: const EdgeInsets.symmetric(horizontal: 20),
                            child: Text("$_quantity", style: GoogleFonts.inter(fontSize: 18, fontWeight: FontWeight.w900)),
                          ),
                          _buildQtyButton(Icons.add, () {
                            setState(() {
                              _quantity++;
                              if (!_selectedExtrasPerItem.containsKey(_quantity - 1)) {
                                _selectedExtrasPerItem[_quantity - 1] = [];
                              }
                            });
                          }),
                        ],
                      ),
                      const SizedBox(height: 32),
                      
                      // DYNAMIC EXTRAS PER ITEM - Only show if product has extras
                      if (_product!.extras.isNotEmpty)
                        for (int i = 0; i < _quantity; i++) ...[
                          Text(_quantity > 1 ? "Tambahan ${i + 1} (Opsional)" : "Tambahan (Opsional)", 
                            style: GoogleFonts.inter(fontSize: 15, fontWeight: FontWeight.w800, color: const Color(0xFF4D4D4D))),
                          const SizedBox(height: 16),
                          _buildExtrasCard(i),
                          const SizedBox(height: 24),
                        ],

                      Text("Catatan Pesanan", style: GoogleFonts.inter(fontSize: 15, fontWeight: FontWeight.w800, color: const Color(0xFF4D4D4D))),
                      const SizedBox(height: 16),
                      Container(
                        decoration: BoxDecoration(
                          color: Colors.white, 
                          borderRadius: BorderRadius.circular(20),
                          boxShadow: [
                            BoxShadow(
                              color: Colors.black.withOpacity(0.04), 
                              blurRadius: 12, 
                              offset: const Offset(0, 4)
                            )
                          ]
                        ),
                        child: TextField(
                          controller: _noteController,
                          maxLines: 3,
                          style: GoogleFonts.inter(
                            fontSize: 14, 
                            fontWeight: FontWeight.w500, 
                            color: const Color(0xFF4D4D4D)
                          ),
                          decoration: InputDecoration(
                            filled: false,
                            hintText: "misal: jangan terlalu pedas...", 
                            border: InputBorder.none, 
                            contentPadding: const EdgeInsets.all(20),
                            hintStyle: GoogleFonts.inter(
                              color: const Color(0xFFBBAA99).withOpacity(0.6),
                              fontSize: 14,
                            ),
                          ),
                        ),
                      ),
                      const SizedBox(height: 140),
                    ],
                  ),
                ),
              ],
            ),
          ),
          Positioned(
            left: 20, right: 20, bottom: 20,
            child: Container(
              decoration: BoxDecoration(boxShadow: [BoxShadow(color: AppColors.primary.withOpacity(0.3), blurRadius: 20, offset: const Offset(0, 10))]),
              child: SizedBox(
                width: double.infinity, height: 60,
                child: ElevatedButton(
                  onPressed: _handleAddToCart,
                  style: ElevatedButton.styleFrom(backgroundColor: AppColors.primary, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)), elevation: 0),
                  child: Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      Text("Tambah ke Keranjang", style: GoogleFonts.inter(fontSize: 16, fontWeight: FontWeight.w800, color: Colors.white)),
                      const SizedBox(width: 8),
                      const Text("|", style: TextStyle(color: Colors.white24)),
                      const SizedBox(width: 8),
                      AnimatedSwitcher(
                        duration: const Duration(milliseconds: 300),
                        transitionBuilder: (Widget child, Animation<double> animation) => FadeTransition(opacity: animation, child: ScaleTransition(scale: animation, child: child)),
                        child: Text("Rp${_totalPrice.toString().replaceAllMapped(RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'), (Match m) => '${m[1]}.')}",
                          key: ValueKey<int>(_totalPrice),
                          style: GoogleFonts.inter(fontSize: 16, fontWeight: FontWeight.w900, color: Colors.white)),
                      ),
                    ],
                  ),
                ),
              ),
            ),
          ),
          Positioned(
            top: 50, left: 20, right: 20, 
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween, 
              children: [
                _buildCircleNav(Icons.chevron_left, () => context.pop()), 
                _buildCircleNav(
                  isFavorite ? Icons.favorite : Icons.favorite_border, 
                  () {
                    ref.read(productProvider.notifier).toggleFavorite(_product!.id);
                    context.push('/favorites');
                  },
                  color: isFavorite ? Colors.white : Colors.white.withOpacity(0.2),
                  iconColor: isFavorite ? AppColors.primary : Colors.white,
                )
              ]
            )
          ),
        ],
      ),
    );
  }

  Widget _buildExtrasCard(int itemIndex) {
    if (_product == null || _product!.extras.isEmpty) return const SizedBox.shrink();
    
    return Container(
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(20),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.03), blurRadius: 10, offset: const Offset(0, 4))]
      ),
      child: Column(
        children: _product!.extras.asMap().entries.map((entry) {
          final extraIndex = entry.key;
          final extra = entry.value;
          final isSelected = _selectedExtrasPerItem[itemIndex]?.contains(extra.name) ?? false;
          final isLast = extraIndex == _product!.extras.length - 1;

          return Column(
            children: [
              GestureDetector(
                onTap: () {
                  HapticFeedback.lightImpact();
                  setState(() {
                    if (!_selectedExtrasPerItem.containsKey(itemIndex)) {
                      _selectedExtrasPerItem[itemIndex] = [];
                    }
                    if (isSelected) {
                      _selectedExtrasPerItem[itemIndex]!.remove(extra.name);
                    } else {
                      _selectedExtrasPerItem[itemIndex]!.add(extra.name);
                    }
                  });
                },
                child: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 16),
                  color: Colors.transparent,
                  child: Row(
                    children: [
                      Container(
                        width: 24, height: 24,
                        decoration: BoxDecoration(
                          color: isSelected ? AppColors.primary : Colors.transparent,
                          borderRadius: BorderRadius.circular(6),
                          border: Border.all(color: isSelected ? AppColors.primary : Colors.grey.withOpacity(0.3), width: 2),
                        ),
                        child: isSelected ? const Icon(Icons.check, size: 16, color: Colors.white) : null,
                      ),
                      const SizedBox(width: 16),
                      Expanded(
                        child: Text(extra.name, style: GoogleFonts.inter(fontSize: 14, fontWeight: FontWeight.w600, color: const Color(0xFF4D4D4D))),
                      ),
                      Text("+Rp${extra.additionalPrice.toString().replaceAllMapped(RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'), (Match m) => '${m[1]}.')}", 
                        style: GoogleFonts.inter(fontSize: 14, fontWeight: FontWeight.w800, color: AppColors.primary)),
                    ],
                  ),
                ),
              ),
              if (!isLast) Divider(height: 1, thickness: 1, color: Colors.grey.withOpacity(0.08), indent: 16, endIndent: 16),
            ],
          );
        }).toList(),
      ),
    );
  }

  Widget _buildHeroHeader() {
    return Container(
      width: double.infinity, height: 350,
      decoration: const BoxDecoration(color: AppColors.primary, borderRadius: BorderRadius.only(bottomLeft: Radius.circular(50), bottomRight: Radius.circular(50))),
      child: Stack(alignment: Alignment.center, children: [_buildRing(200, Colors.white.withOpacity(0.05)), _buildRing(260, Colors.white.withOpacity(0.03)), _buildRing(320, Colors.white.withOpacity(0.02)), Hero(tag: "product_${_product!.id}", child: Image.asset(_getProductAsset(_product!.name), width: 250, height: 250, fit: BoxFit.contain))]),
    );
  }

  Widget _buildRing(double size, Color color) => Container(width: size, height: size, decoration: BoxDecoration(shape: BoxShape.circle, border: Border.all(color: color, width: 20)));
  Widget _buildCircleNav(IconData icon, VoidCallback onTap, {Color? color, Color? iconColor}) => GestureDetector(onTap: onTap, child: Container(padding: const EdgeInsets.all(10), decoration: BoxDecoration(color: color ?? Colors.white.withOpacity(0.2), shape: BoxShape.circle), child: Icon(icon, color: iconColor ?? Colors.white, size: 24)));
  Widget _buildQtyButton(IconData icon, VoidCallback onTap) => GestureDetector(onTap: onTap, child: Container(padding: const EdgeInsets.all(8), decoration: const BoxDecoration(color: Color(0xFFEBE2D5), shape: BoxShape.circle), child: Icon(icon, color: const Color(0xFF4D4D4D), size: 20)));
}
