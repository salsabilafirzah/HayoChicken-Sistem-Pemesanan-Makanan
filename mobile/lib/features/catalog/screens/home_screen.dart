import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import '../providers/product_provider.dart';
import '../../auth/providers/auth_provider.dart';
import '../../cart/providers/cart_provider.dart';
import '../../../core/theme/app_theme.dart';

class HomeScreen extends ConsumerStatefulWidget {
  const HomeScreen({super.key});

  @override
  ConsumerState<HomeScreen> createState() => _HomeScreenState();
}

class _HomeScreenState extends ConsumerState<HomeScreen> {
  final PageController _bannerController = PageController();
  final TextEditingController _searchController = TextEditingController();
  int _currentBanner = 0;
  int? _selectedCategoryId;

  @override
  void dispose() {
    _searchController.dispose();
    _bannerController.dispose();
    super.dispose();
  }

  String _getCategoryIcon(String name) {
    String n = name.toLowerCase();
    if (n.contains("ayam")) return "assets/images/fried_chicken.png";
    if (n.contains("mie")) return "assets/images/mie_jebew.png";
    if (n.contains("minum") || n.contains("lemon")) return "assets/images/lemon_tea.png";
    if (n.contains("paket")) return "assets/images/paket_combo.png";
    if (n.contains("cemilan") || n.contains("snack")) return "assets/images/cemilan-pastel.png";
    return "assets/images/fried_chicken.png";
  }

  @override
  Widget build(BuildContext context) {
    final state = ref.watch(productProvider);
    final authState = ref.watch(authProvider);
    final userName = authState.user?.name ?? "...";

    final filteredProducts = state.products.where((p) {
      final matchesCategory = _selectedCategoryId == null || p.categoryId == _selectedCategoryId;
      final matchesSearch = p.name.toLowerCase().contains(_searchController.text.toLowerCase());
      return matchesCategory && matchesSearch;
    }).toList();

    return Column(
      children: [
        Container(
          padding: const EdgeInsets.fromLTRB(24, 60, 24, 34),
          decoration: const BoxDecoration(
            color: AppColors.primary,
            borderRadius: BorderRadius.only(bottomLeft: Radius.circular(45), bottomRight: Radius.circular(45)),
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
                      Text("Halo, selamat datang", style: GoogleFonts.inter(color: Colors.white70, fontSize: 13, fontWeight: FontWeight.w500)),
                      Text(userName, style: GoogleFonts.inter(color: Colors.white, fontSize: 24, fontWeight: FontWeight.w900)),
                    ],
                  ),
                  GestureDetector(
                    onTap: () => context.push('/profile/notif'),
                    child: Stack(
                      children: [
                        Container(
                          width: 48, height: 48,
                          decoration: BoxDecoration(color: Colors.white.withOpacity(0.2), shape: BoxShape.circle),
                          child: const Icon(Icons.notifications_none, color: Colors.white, size: 26),
                        ),
                        Positioned(
                          right: 4, top: 4,
                          child: Container(
                            width: 12, height: 12,
                            decoration: BoxDecoration(color: const Color(0xFFFF4B4B), shape: BoxShape.circle, border: Border.all(color: AppColors.primary, width: 2)),
                          ),
                        ),
                      ],
                    ),
                  ),
                ],
              ),
              const SizedBox(height: 22),
              Container(
                height: 54,
                padding: const EdgeInsets.symmetric(horizontal: 20),
                decoration: BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.circular(50),
                  boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.08), blurRadius: 15, offset: const Offset(0, 5))],
                ),
                child: Row(
                  children: [
                    const Icon(Icons.search, color: AppColors.primary, size: 24),
                    const SizedBox(width: 12),
                    Expanded(
                      child: TextField(
                        controller: _searchController,
                        onChanged: (v) => setState(() {}),
                        cursorColor: AppColors.primary,
                        decoration: InputDecoration(
                          hintText: "Lagi mau mam apa?",
                          border: InputBorder.none,
                          isDense: true,
                          filled: false,
                          fillColor: Colors.transparent,
                          hoverColor: Colors.transparent,
                          contentPadding: EdgeInsets.zero,
                          hintStyle: GoogleFonts.inter(color: const Color(0xFFBBAA99).withOpacity(0.8), fontSize: 14, fontWeight: FontWeight.w500),
                        ),
                        style: GoogleFonts.inter(color: const Color(0xFF1A1A1A), fontSize: 14, fontWeight: FontWeight.w700),
                      ),
                    ),
                  ],
                ),
              ),
            ],
          ),
        ),

        Expanded(
          child: ListView(
            padding: EdgeInsets.zero,
            children: [
              const SizedBox(height: 24),
              SizedBox(
                height: 130,
                child: ListView.builder(
                  scrollDirection: Axis.horizontal,
                  padding: const EdgeInsets.symmetric(horizontal: 16),
                  itemCount: state.categories.length,
                  itemBuilder: (context, index) {
                    final cat = state.categories[index];
                    final isSelected = _selectedCategoryId == cat.id;
                    return GestureDetector(
                      onTap: () => setState(() {
                        if (_selectedCategoryId == cat.id) {
                          _selectedCategoryId = null;
                        } else {
                          _selectedCategoryId = cat.id;
                        }
                      }),
                      child: Container(
                        width: 95,
                        margin: const EdgeInsets.only(right: 14),
                        child: Column(
                          children: [
                            Container(
                              width: 95, height: 95,
                              decoration: BoxDecoration(
                                color: isSelected ? AppColors.primary.withOpacity(0.1) : const Color(0xFFEDE0D0), 
                                borderRadius: BorderRadius.circular(28),
                                border: isSelected ? Border.all(color: AppColors.primary, width: 2) : null,
                              ),
                              child: Center(
                                child: Image.asset(
                                  _getCategoryIcon(cat.name), 
                                  width: 60, height: 60,
                                  errorBuilder: (c, e, s) => const Icon(Icons.fastfood, color: AppColors.primary, size: 40),
                                ),
                              ),
                            ),
                            const SizedBox(height: 8),
                            Text(
                              cat.name, 
                              style: GoogleFonts.inter(
                                fontSize: 12, 
                                fontWeight: isSelected ? FontWeight.w900 : FontWeight.w700, 
                                color: isSelected ? AppColors.primary : const Color(0xFF555555)
                              )
                            ),
                          ],
                        ),
                      ),
                    );
                  },
                ),
              ),

              const SizedBox(height: 24),
              Column(
                children: [
                  SizedBox(
                    height: 180,
                    child: PageView(
                      controller: _bannerController,
                      onPageChanged: (v) => setState(() => _currentBanner = v),
                      children: [
                        _buildBanner(const Color(0xFFE67E22), "Best Seller", "Ayam geprek + sambal matah", "Pesan Sekarang", "assets/images/ayam_geprek.png", 1),
                        _buildBanner(const Color(0xFF27AE60), "Promo Jumat", "Free es teh setiap hari jumat", "Klaim Promo", "assets/images/three_lemon_teas.png", 4),
                        _buildBanner(const Color(0xFF2980B9), "Hemat Banget", "Paket nasi + ayam cuma 15rb", "Cek Menu", "assets/images/paket_nasi_mie.png", 5),
                      ],
                    ),
                  ),
                  const SizedBox(height: 12),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: List.generate(3, (i) => Container(
                      width: _currentBanner == i ? 24 : 8, height: 8,
                      margin: const EdgeInsets.symmetric(horizontal: 3),
                      decoration: BoxDecoration(color: _currentBanner == i ? AppColors.primary : const Color(0xFFBBAA99).withOpacity(0.5), borderRadius: BorderRadius.circular(10)),
                    )),
                  ),
                ],
              ),

              Padding(
                padding: const EdgeInsets.fromLTRB(24, 35, 24, 20),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.spaceBetween,
                  children: [
                    Text("Menu Populer", style: GoogleFonts.inter(fontSize: 18, fontWeight: FontWeight.w900, color: const Color(0xFF1A1A1A))),
                    Text("Lihat Semua", style: GoogleFonts.inter(color: AppColors.primary.withOpacity(0.6), fontSize: 12, fontWeight: FontWeight.w900)),
                  ],
                ),
              ),

              if (state.isLoading)
                const Center(child: CircularProgressIndicator())
              else
                ...[
                  GridView.builder(
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    padding: const EdgeInsets.symmetric(horizontal: 18),
                    gridDelegate: const SliverGridDelegateWithFixedCrossAxisCount(crossAxisCount: 2, childAspectRatio: 0.63, crossAxisSpacing: 14, mainAxisSpacing: 14),
                    itemCount: filteredProducts.length,
                    itemBuilder: (context, index) {
                      return _ProductCard(product: filteredProducts[index]);
                    },
                  ),
                  if (filteredProducts.isEmpty)
                    Center(
                      child: Padding(
                        padding: const EdgeInsets.symmetric(vertical: 40),
                        child: Text(
                          _searchController.text.isEmpty 
                            ? "Menu tidak tersedia untuk kategori ini" 
                            : "Menu yang kamu cari tidak ditemukan", 
                          style: GoogleFonts.inter(color: Colors.grey, fontWeight: FontWeight.w600)
                        ),
                      ),
                    ),
                ],
              const SizedBox(height: 180),
            ],
          ),
        ),
      ],
    );
  }

  Widget _buildBanner(Color color, String title, String sub, String btn, String asset, int productId) {
    return GestureDetector(
      onTap: () {
        if (btn == "Klaim Promo") {
          _showPromoDialog(context);
        } else {
          context.push('/product/$productId');
        }
      },
      child: Container(
        margin: const EdgeInsets.symmetric(horizontal: 20),
        padding: const EdgeInsets.all(24),
        decoration: BoxDecoration(
          gradient: LinearGradient(colors: [color, color.withOpacity(0.8)], begin: Alignment.topLeft, end: Alignment.bottomRight),
          borderRadius: BorderRadius.circular(35),
        ),
        child: Row(
          children: [
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  Text(title, style: GoogleFonts.inter(color: Colors.white, fontSize: 24, fontWeight: FontWeight.w900)),
                  Text(sub, style: GoogleFonts.inter(color: Colors.white.withOpacity(0.9), fontSize: 12, fontWeight: FontWeight.w600)),
                  const SizedBox(height: 16),
                  Container(
                    padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 10),
                    decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(50)),
                    child: Text(btn, style: GoogleFonts.inter(color: color, fontSize: 11, fontWeight: FontWeight.w900)),
                  ),
                ],
              ),
            ),
            Hero(
              tag: "banner_$productId",
              child: Image.asset(asset, width: 130, height: 130, fit: BoxFit.contain),
            ),
          ],
        ),
      ),
    );
  }

  void _showPromoDialog(BuildContext context) {
    showDialog(
      context: context,
      builder: (context) => Dialog(
        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(35)),
        backgroundColor: Colors.white,
        child: Padding(
          padding: const EdgeInsets.all(32),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Container(
                width: 90, height: 90,
                decoration: const BoxDecoration(color: Color(0xFFFFF9F0), shape: BoxShape.circle),
                child: const Icon(Icons.check_rounded, color: Color(0xFFF1B434), size: 50),
              ),
              const SizedBox(height: 24),
              Text("Promo Berhasil!", style: GoogleFonts.inter(fontSize: 22, fontWeight: FontWeight.w900, color: const Color(0xFF1A1A1A))),
              const SizedBox(height: 14),
              Text(
                "Kupon promo berhasil diklaim & terpasang! Kamu akan mendapatkan Es Teh Lemon gratis untuk setiap pembelian Paket Nasi Ayam.",
                textAlign: TextAlign.center,
                style: GoogleFonts.inter(fontSize: 13, color: Colors.black54, height: 1.5, fontWeight: FontWeight.w500),
              ),
              const SizedBox(height: 32),
              Row(
                children: [
                  Expanded(
                    child: ElevatedButton(
                      onPressed: () => Navigator.pop(context),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: const Color(0xFFF5F5F5),
                        foregroundColor: Colors.black54,
                        elevation: 0,
                        padding: const EdgeInsets.symmetric(vertical: 18),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50)),
                      ),
                      child: Text("Lanjut\nBelanja", textAlign: TextAlign.center, style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 13)),
                    ),
                  ),
                  const SizedBox(width: 12),
                  Expanded(
                    child: ElevatedButton(
                      onPressed: () {
                        Navigator.pop(context);
                        context.push('/cart');
                      },
                      style: ElevatedButton.styleFrom(
                        backgroundColor: AppColors.primary,
                        elevation: 10,
                        shadowColor: AppColors.primary.withOpacity(0.4),
                        padding: const EdgeInsets.symmetric(vertical: 18),
                        shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50)),
                      ),
                      child: Text("Lihat\nKeranjang", textAlign: TextAlign.center, style: GoogleFonts.inter(color: Colors.white, fontWeight: FontWeight.w800, fontSize: 13)),
                    ),
                  ),
                ],
              ),
            ],
          ),
        ),
      ),
    );
  }

  void _showOrderListModal(BuildContext context) {
    showModalBottomSheet(
      context: context,
      backgroundColor: Colors.transparent,
      isScrollControlled: true,
      builder: (context) => Container(
        padding: const EdgeInsets.fromLTRB(28, 32, 28, 40),
        decoration: const BoxDecoration(
          color: Color(0xFFF9F4EB),
          borderRadius: BorderRadius.only(topLeft: Radius.circular(40), topRight: Radius.circular(40)),
        ),
        child: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text("Pesananku", style: GoogleFonts.inter(fontSize: 22, fontWeight: FontWeight.w900, color: const Color(0xFF1A1A1A))),
                GestureDetector(
                  onTap: () => Navigator.pop(context),
                  child: Text("Tutup", style: GoogleFonts.inter(color: AppColors.primary, fontWeight: FontWeight.w800, fontSize: 15)),
                ),
              ],
            ),
            const SizedBox(height: 28),
            _buildOrderItem(context, "#HC-20260610-0001", "Diproses (Baru)", const Color(0xFF27AE60)),
            _buildOrderItem(context, "#HC-20260608-MOD-1", "Siap Diambil", const Color(0xFF27AE60)),
            _buildOrderItem(context, "#HC-20260608-MOD-2", "Menunggu Verifikasi", Colors.orange),
            _buildOrderItem(context, "#HC-20260608-0003", "Sedang Dimasak", Colors.blue),
            _buildOrderItem(context, "#HC-20260608-0001", "Siap Diambil", const Color(0xFF27AE60)),
          ],
        ),
      ),
    );
  }

  Widget _buildOrderItem(BuildContext context, String id, String status, Color color) {
    return GestureDetector(
      onTap: () {
        Navigator.pop(context);
        context.push('/profile/orders');
      },
      child: Container(
        margin: const EdgeInsets.only(bottom: 16),
        padding: const EdgeInsets.all(18),
        decoration: BoxDecoration(
          color: Colors.white, 
          borderRadius: BorderRadius.circular(22),
          boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.02), blurRadius: 10, offset: const Offset(0, 4))],
        ),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(color: AppColors.primary.withOpacity(0.06), borderRadius: BorderRadius.circular(15)),
              child: const Icon(Icons.shopping_bag_outlined, color: AppColors.primary, size: 24),
            ),
            const SizedBox(width: 18),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text("Pesanan $id", style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 14, color: const Color(0xFF1A1A1A))),
                  const SizedBox(height: 2),
                  Text(status, style: GoogleFonts.inter(color: color, fontSize: 12, fontWeight: FontWeight.w800)),
                ],
              ),
            ),
          ],
        ),
      ),
    );
  }
}

class _ProductCard extends ConsumerWidget {
  final dynamic product;
  const _ProductCard({required this.product});

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
      margin: const EdgeInsets.only(bottom: 2),
      decoration: BoxDecoration(
        color: const Color(0xFFF1E9DA), 
        borderRadius: BorderRadius.circular(35), 
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 15, offset: const Offset(0, 8))]
      ),
      child: Stack(
        children: [
          // 1. MAIN CARD NAVIGATION (Base Layer)
          Positioned.fill(
            child: InkWell(
              onTap: () => context.push('/product/${product.id}'),
              borderRadius: BorderRadius.circular(35),
              child: const SizedBox.expand(),
            ),
          ),

          // 2. VISUAL CONTENT
          Column(
            children: [
              // Wave & Image Area
              ClipPath(
                clipper: _WaveClipper(),
                child: Container(
                  height: 120, width: double.infinity,
                  decoration: const BoxDecoration(
                    color: Color(0xFF8B1A1A), 
                    borderRadius: BorderRadius.only(topLeft: Radius.circular(35), topRight: Radius.circular(35))
                  ),
                  child: Center(
                    child: Hero(
                      tag: "product_${product.id}",
                      child: Image.asset(_getProductAsset(product.name), width: 135, height: 135, fit: BoxFit.contain),
                    ),
                  ),
                ),
              ),
              const SizedBox(height: 35),
              Padding(
                padding: const EdgeInsets.fromLTRB(16, 0, 16, 16),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(product.name, style: GoogleFonts.inter(fontSize: 14, fontWeight: FontWeight.w900), maxLines: 1),
                    Text(product.description ?? "", style: GoogleFonts.inter(fontSize: 10, color: Colors.grey, fontWeight: FontWeight.w600), maxLines: 1),
                    const SizedBox(height: 12),
                    Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Text("Rp ${product.basePrice}", style: GoogleFonts.inter(fontSize: 15, fontWeight: FontWeight.w900, color: AppColors.primary)),
                        
                        // 3. ACTION BUTTON (Direct Add and Update Badge)
                        Material(
                          color: const Color(0xFF8B1A1A),
                          shape: const CircleBorder(),
                          child: InkWell(
                            onTap: () async {
                              await ref.read(cartProvider.notifier).addToCart(
                                productId: product.id, 
                                quantity: 1, 
                                extras: []
                              );
                              // NO NAVIGATION HERE
                            },
                            customBorder: const CircleBorder(),
                            child: const Padding(
                              padding: EdgeInsets.all(8),
                              child: Icon(Icons.add, color: Colors.white, size: 20),
                            ),
                          ),
                        ),
                      ],
                    ),
                  ],
                ),
              ),
            ],
          ),

          // 4. FAVORITE BUTTON (Top Corner)
          Positioned(
            top: 15, right: 15,
            child: Material(
              color: Colors.white,
              shape: const CircleBorder(),
              elevation: 4,
              shadowColor: Colors.black26,
              child: InkWell(
                onTap: () {
                  ref.read(productProvider.notifier).toggleFavorite(product.id);
                  context.push('/favorites');
                },
                customBorder: const CircleBorder(),
                child: Padding(
                  padding: const EdgeInsets.all(10),
                  child: Icon(
                    isFavorite ? Icons.favorite : Icons.favorite_border, 
                    color: isFavorite ? AppColors.primary : const Color(0xFFBBAA99), 
                    size: 18,
                  ),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }
}

class _WaveClipper extends CustomClipper<Path> {
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
