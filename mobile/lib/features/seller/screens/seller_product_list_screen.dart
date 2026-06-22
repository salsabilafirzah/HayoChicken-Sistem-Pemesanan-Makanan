import 'dart:io';
import 'package:dio/dio.dart';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:intl/intl.dart';
import 'package:image_picker/image_picker.dart';
import '../../auth/providers/auth_provider.dart';
import '../../catalog/providers/product_provider.dart';
import '../../catalog/models/product_model.dart';
import '../../catalog/models/category_model.dart';
import '../../../core/theme/app_theme.dart';
import '../../../core/network/api_service.dart';
import '../../../core/constants/constants.dart';

// Local provider to fetch ALL products (including unavailable ones) for the Seller
final sellerProductProvider = StateNotifierProvider.autoDispose<SellerProductNotifier, ProductState>((ref) {
  return SellerProductNotifier();
});

class SellerProductNotifier extends StateNotifier<ProductState> {
  SellerProductNotifier() : super(ProductState()) {
    loadData();
  }

  Future<void> loadData() async {
    state = state.copyWith(isLoading: true);
    try {
      final api = ApiService();
      // Fetch products with show_all=true
      final res = await api.get('/products?show_all=true');
      final List productsData = res.data['data']['data'] ?? [];
      final products = productsData.map((e) => ProductModel.fromJson(e)).toList();

      // Fetch categories
      final catRes = await api.get('/categories');
      final List catData = catRes.data['data'] ?? [];
      final categories = catData.map((e) => CategoryModel.fromJson(e)).toList();

      state = state.copyWith(
        products: products,
        categories: categories,
        isLoading: false,
      );
    } catch (e) {
      state = state.copyWith(isLoading: false);
    }
  }
}

class SellerProductListScreen extends ConsumerWidget {
  const SellerProductListScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final state = ref.watch(sellerProductProvider);

    return Scaffold(
      backgroundColor: const Color(0xFFF8EFDE),
      body: Stack(
        children: [
          Column(
            children: [
              // HEADER
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
                            showDialog(
                              context: context,
                              builder: (ctx) => Dialog(
                                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(24)),
                                backgroundColor: Colors.white,
                                child: Padding(
                                  padding: const EdgeInsets.all(24),
                                  child: Column(
                                    mainAxisSize: MainAxisSize.min,
                                    children: [
                                      Container(
                                        padding: const EdgeInsets.all(16),
                                        decoration: const BoxDecoration(color: Color(0xFFFDE8E8), shape: BoxShape.circle),
                                        child: const Icon(Icons.logout_rounded, color: AppColors.primary, size: 32),
                                      ),
                                      const SizedBox(height: 20),
                                      Text("Keluar Akun?", style: GoogleFonts.inter(fontSize: 20, fontWeight: FontWeight.w900, color: const Color(0xFF1A1A1A))),
                                      const SizedBox(height: 8),
                                      Text(
                                        "Sesi Anda akan diakhiri. Apakah Anda yakin ingin keluar dari akun Penjual?",
                                        textAlign: TextAlign.center,
                                        style: GoogleFonts.inter(fontSize: 13, color: Colors.grey[600]),
                                      ),
                                      const SizedBox(height: 28),
                                      Row(
                                        children: [
                                          Expanded(
                                            child: OutlinedButton(
                                              style: OutlinedButton.styleFrom(
                                                padding: const EdgeInsets.symmetric(vertical: 14),
                                                side: BorderSide(color: Colors.grey[300]!, width: 2),
                                                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                                              ),
                                              onPressed: () => Navigator.pop(ctx),
                                              child: Text("Batal", style: GoogleFonts.inter(fontWeight: FontWeight.w700, color: Colors.grey[700])),
                                            ),
                                          ),
                                          const SizedBox(width: 12),
                                          Expanded(
                                            child: ElevatedButton(
                                              style: ElevatedButton.styleFrom(
                                                backgroundColor: AppColors.primary,
                                                padding: const EdgeInsets.symmetric(vertical: 14),
                                                elevation: 0,
                                                shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
                                              ),
                                              onPressed: () {
                                                Navigator.pop(ctx);
                                                ref.read(authProvider.notifier).logout();
                                                while(Navigator.canPop(context)) { Navigator.pop(context); }
                                                GoRouter.of(context).go('/login');
                                              },
                                              child: Text("Ya, Keluar", style: GoogleFonts.inter(fontWeight: FontWeight.w700, color: Colors.white)),
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
                          child: Container(
                            width: 44, height: 44,
                            decoration: BoxDecoration(color: Colors.white.withAlpha((0.2 * 255).toInt()), shape: BoxShape.circle),
                            child: const Icon(Icons.logout, color: Colors.white, size: 20),
                          ),
                        ),
                      ],
                    ),
                    const SizedBox(height: 20),
                    Row(
                      children: [
                        Expanded(child: _kpiCard(Icons.fastfood_outlined, "${state.products.length}", "Total Produk")),
                        const SizedBox(width: 12),
                        Expanded(child: _kpiCard(Icons.category_outlined, "${state.categories.length}", "Kategori")),
                      ],
                    ),
                  ],
                ),
              ),

              // Product List
              Expanded(
                child: state.isLoading
                  ? const Center(child: CircularProgressIndicator(color: AppColors.primary))
                  : ListView.builder(
                      padding: const EdgeInsets.fromLTRB(16, 24, 16, 120),
                      itemCount: state.products.length,
                      itemBuilder: (context, index) {
                        final product = state.products[index];
                        return _ProductCard(
                          product: product,
                          onEditDone: () => ref.refresh(sellerProductProvider),
                        );
                      },
                    ),
              ),
            ],
          ),
        ],
      ),
      floatingActionButton: Padding(
        padding: const EdgeInsets.only(bottom: 110),
        child: FloatingActionButton(
          onPressed: () {
            showModalBottomSheet(
              context: context,
              isScrollControlled: true,
              backgroundColor: Colors.transparent,
              builder: (ctx) => _AddProductSheet(
                categories: state.categories,
                onSaved: () => ref.refresh(sellerProductProvider),
              ),
            );
          },
          backgroundColor: const Color(0xFFF5A623),
          shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20)),
          elevation: 8,
          child: const Icon(Icons.add, color: Colors.white, size: 30),
        ),
      ),
    );
  }

  Widget _kpiCard(IconData icon, String value, String label) {
    return Container(
      padding: const EdgeInsets.all(12),
      decoration: BoxDecoration(
        color: Colors.white.withAlpha((0.12 * 255).toInt()),
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

// ─────────────────────────────────────────────────────────────────
// Product Card Widget
// ─────────────────────────────────────────────────────────────────
class _ProductCard extends StatelessWidget {
  final ProductModel product;
  final VoidCallback onEditDone;

  const _ProductCard({required this.product, required this.onEditDone});

  String _getProductAsset(String name) {
    String n = name.toLowerCase();
    if (n.contains("geprek")) return "assets/images/ayam_geprek.png";
    if (n.contains("crispy") || n.contains("krispi")) return "assets/images/fried_chicken.png";
    if (n.contains("jebew")) return "assets/images/mie_jebew.png";
    if (n.contains("lemon")) return "assets/images/lemon_tea.png";
    if (n.contains("rice bowl")) return "assets/images/rice_bowl.png";
    if (n.contains("combo") || n.contains("paket")) return "assets/images/paket_combo.png";
    return "assets/images/fried_chicken.png";
  }

  @override
  Widget build(BuildContext context) {
    final formatter = NumberFormat.currency(locale: 'id_ID', symbol: 'Rp', decimalDigits: 0);

    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.all(16),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(30),
        boxShadow: [BoxShadow(color: Colors.black.withAlpha((0.03 * 255).toInt()), blurRadius: 15, offset: const Offset(0, 8))],
      ),
      child: Row(
        children: [
          // Product Image — matches buyer product card style
          ClipRRect(
            borderRadius: BorderRadius.circular(16),
            child: SizedBox(
              width: 70,
              height: 70,
              child: product.imageUrl != null && product.imageUrl!.isNotEmpty
                ? Image.network(
                    AppConstants.baseUrl.replaceAll('/api/v1', '') + product.imageUrl!,
                    fit: BoxFit.cover,
                    errorBuilder: (_, __, ___) => Image.asset(
                      _getProductAsset(product.name),
                      fit: BoxFit.cover,
                      errorBuilder: (c, e, s) => Container(
                        color: const Color(0xFFF5F5F5),
                        child: const Icon(Icons.restaurant, color: Color(0xFFD32F2F), size: 34),
                      ),
                    ),
                  )
                : Image.asset(
                    _getProductAsset(product.name),
                    fit: BoxFit.cover,
                    errorBuilder: (c, e, s) => Container(
                      color: const Color(0xFFF5F5F5),
                      child: const Icon(Icons.restaurant, color: Color(0xFFD32F2F), size: 34),
                    ),
                  ),
            ),
          ),
          const SizedBox(width: 16),
          // Product Info
          Expanded(
            child: Column(
              crossAxisAlignment: CrossAxisAlignment.start,
              children: [
                Text(
                  product.name,
                  style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 15, color: const Color(0xFF1A1A1A)),
                  maxLines: 1,
                  overflow: TextOverflow.ellipsis,
                ),
                const SizedBox(height: 2),
                Text(
                  formatter.format(product.basePrice),
                  style: GoogleFonts.inter(color: AppColors.primary, fontWeight: FontWeight.w900, fontSize: 15),
                ),
                const SizedBox(height: 2),
                Row(
                  children: [
                    Icon(
                      product.isAvailable ? Icons.check_circle : Icons.cancel,
                      size: 12,
                      color: product.isAvailable ? Colors.green : Colors.red,
                    ),
                    const SizedBox(width: 4),
                    Text(
                      product.isAvailable ? "Tersedia" : "Habis",
                      style: GoogleFonts.inter(
                        color: product.isAvailable ? Colors.green : Colors.red,
                        fontSize: 11,
                        fontWeight: FontWeight.w600,
                      ),
                    ),
                  ],
                ),
              ],
            ),
          ),
          // Edit Button
          OutlinedButton(
            onPressed: () => _showEditSheet(context),
            style: OutlinedButton.styleFrom(
              foregroundColor: AppColors.primary,
              side: const BorderSide(color: AppColors.primary, width: 2),
              shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50)),
              minimumSize: const Size(72, 38),
              padding: const EdgeInsets.symmetric(horizontal: 16),
            ),
            child: Text("Edit", style: GoogleFonts.inter(fontWeight: FontWeight.bold, fontSize: 13)),
          ),
        ],
      ),
    );
  }

  void _showEditSheet(BuildContext context) {
    showModalBottomSheet(
      context: context,
      isScrollControlled: true,
      backgroundColor: Colors.transparent,
      builder: (ctx) => _EditProductSheet(product: product, onSaved: onEditDone),
    );
  }
}

// ─────────────────────────────────────────────────────────────────
// Edit Product Bottom Sheet
// ─────────────────────────────────────────────────────────────────
class _EditProductSheet extends StatefulWidget {
  final ProductModel product;
  final VoidCallback onSaved;

  const _EditProductSheet({required this.product, required this.onSaved});

  @override
  State<_EditProductSheet> createState() => _EditProductSheetState();
}

class _EditProductSheetState extends State<_EditProductSheet> {
  late TextEditingController _nameCtrl;
  late TextEditingController _priceCtrl;
  late TextEditingController _descCtrl;
  late bool _isAvailable;
  File? _selectedImage;
  bool _isSaving = false;

  @override
  void initState() {
    super.initState();
    _nameCtrl = TextEditingController(text: widget.product.name);
    _priceCtrl = TextEditingController(text: widget.product.basePrice.toString());
    _descCtrl = TextEditingController(text: widget.product.description ?? '');
    _isAvailable = widget.product.isAvailable;
  }

  @override
  void dispose() {
    _nameCtrl.dispose();
    _priceCtrl.dispose();
    _descCtrl.dispose();
    super.dispose();
  }

  Future<void> _pickImage() async {
    final picker = ImagePicker();
    final pickedFile = await picker.pickImage(source: ImageSource.gallery);
    if (pickedFile != null) {
      setState(() => _selectedImage = File(pickedFile.path));
    }
  }

  Future<void> _save() async {
    if (_nameCtrl.text.trim().isEmpty || _priceCtrl.text.trim().isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("Nama dan harga tidak boleh kosong!"), backgroundColor: Colors.red));
      return;
    }

    setState(() => _isSaving = true);
    try {
      final api = ApiService();
      
      // Use multipart for image upload if image selected
      if (_selectedImage != null) {
        final formData = FormData.fromMap({
          '_method': 'PATCH', // Laravel workaround for multipart PATCH
          'name': _nameCtrl.text.trim(),
          'base_price': int.tryParse(_priceCtrl.text.trim()) ?? widget.product.basePrice,
          'description': _descCtrl.text.trim(),
          'is_available': _isAvailable ? 1 : 0,
          'image': await MultipartFile.fromFile(_selectedImage!.path),
        });
        
        final res = await api.post('/seller/products/${widget.product.id}', data: formData);
        
        if (!mounted) return;
        if (res.data['success'] == true) {
          Navigator.pop(context);
          ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("Produk & Gambar berhasil diperbarui!"), backgroundColor: Colors.green));
          widget.onSaved();
        } else {
          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(res.data['message'] ?? 'Gagal menyimpan'), backgroundColor: Colors.red));
        }
      } else {
        // Direct PATCH if no image changed
        final res = await api.patch('/seller/products/${widget.product.id}', data: {
          'name': _nameCtrl.text.trim(),
          'base_price': int.tryParse(_priceCtrl.text.trim()) ?? widget.product.basePrice,
          'description': _descCtrl.text.trim(),
          'is_available': _isAvailable ? 1 : 0,
        });

        if (!mounted) return;
        if (res.data['success'] == true) {
          Navigator.pop(context);
          ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("Produk berhasil diperbarui!"), backgroundColor: Colors.green));
          widget.onSaved();
        } else {
          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(res.data['message'] ?? 'Gagal menyimpan'), backgroundColor: Colors.red));
        }
      }
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text("Error: ${e.toString()}"), backgroundColor: Colors.red));
    } finally {
      if (mounted) setState(() => _isSaving = false);
    }
  }

  String _getProductAsset(String name) {
    String n = name.toLowerCase();
    if (n.contains("geprek")) return "assets/images/ayam_geprek.png";
    if (n.contains("crispy") || n.contains("krispi")) return "assets/images/fried_chicken.png";
    if (n.contains("jebew")) return "assets/images/mie_jebew.png";
    if (n.contains("lemon")) return "assets/images/lemon_tea.png";
    if (n.contains("rice bowl")) return "assets/images/rice_bowl.png";
    if (n.contains("combo") || n.contains("paket")) return "assets/images/paket_combo.png";
    return "assets/images/fried_chicken.png";
  }

  @override
  Widget build(BuildContext context) {
    final bottomInset = MediaQuery.of(context).viewInsets.bottom;

    return Container(
      margin: const EdgeInsets.fromLTRB(12, 0, 12, 12),
      padding: EdgeInsets.fromLTRB(24, 24, 24, 24 + bottomInset),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(30)),
      child: SingleChildScrollView(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Handle bar
            Center(child: Container(width: 40, height: 4, decoration: BoxDecoration(color: Colors.grey[300], borderRadius: BorderRadius.circular(4)))),
            const SizedBox(height: 20),
            Text("Edit Menu", style: GoogleFonts.inter(fontWeight: FontWeight.w900, fontSize: 20, color: const Color(0xFF1A1A1A))),
            Text("Perubahan akan langsung tersimpan ke database", style: GoogleFonts.inter(fontSize: 12, color: Colors.grey[500])),
            const SizedBox(height: 20),

            // Image Preview & Edit
            Center(
              child: Column(
                children: [
                  GestureDetector(
                    onTap: _pickImage,
                    child: Stack(
                      children: [
                        Container(
                          width: 100, height: 100,
                          decoration: BoxDecoration(
                            color: const Color(0xFFF8EFDE),
                            borderRadius: BorderRadius.circular(20),
                            border: Border.all(color: AppColors.primary.withValues(alpha: 0.2), width: 2),
                          ),
                          child: ClipRRect(
                            borderRadius: BorderRadius.circular(18),
                            child: _selectedImage != null
                              ? Image.file(_selectedImage!, fit: BoxFit.cover)
                              : (widget.product.imageUrl != null && widget.product.imageUrl!.isNotEmpty
                                ? Image.network(
                                    AppConstants.baseUrl.replaceAll('/api/v1', '') + widget.product.imageUrl!,
                                    fit: BoxFit.cover,
                                    errorBuilder: (c, e, s) => Image.asset(_getProductAsset(widget.product.name), fit: BoxFit.cover),
                                  )
                                : Image.asset(_getProductAsset(widget.product.name), fit: BoxFit.cover)),
                          ),
                        ),
                        Positioned(
                          right: 0, bottom: 0,
                          child: Container(
                            padding: const EdgeInsets.all(6),
                            decoration: const BoxDecoration(color: AppColors.primary, shape: BoxShape.circle),
                            child: const Icon(Icons.camera_alt, color: Colors.white, size: 16),
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text("Ketuk untuk ganti foto", style: GoogleFonts.inter(fontSize: 11, color: AppColors.primary, fontWeight: FontWeight.w700)),
                ],
              ),
            ),
            const SizedBox(height: 24),

            // Name field
            Text("Nama Menu", style: GoogleFonts.inter(fontWeight: FontWeight.w700, fontSize: 13, color: const Color(0xFF333333))),
            const SizedBox(height: 8),
            _inputField(_nameCtrl, "Nama produk", TextInputType.text),
            const SizedBox(height: 16),

            // Price field
            Text("Harga (Rp)", style: GoogleFonts.inter(fontWeight: FontWeight.w700, fontSize: 13, color: const Color(0xFF333333))),
            const SizedBox(height: 8),
            _inputField(_priceCtrl, "Harga dalam rupiah", TextInputType.number),
            const SizedBox(height: 16),

            // Description field
            Text("Deskripsi", style: GoogleFonts.inter(fontWeight: FontWeight.w700, fontSize: 13, color: const Color(0xFF333333))),
            const SizedBox(height: 8),
            _inputField(_descCtrl, "Deskripsi singkat menu", TextInputType.multiline, maxLines: 2),
            const SizedBox(height: 16),

            // Availability toggle
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
              decoration: BoxDecoration(color: const Color(0xFFF8EFDE), borderRadius: BorderRadius.circular(16)),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text("Status Menu", style: GoogleFonts.inter(fontWeight: FontWeight.w700, fontSize: 13)),
                      Text(_isAvailable ? "Tersedia untuk dipesan" : "Menu sedang habis", style: GoogleFonts.inter(fontSize: 11, color: Colors.grey[600])),
                    ],
                  ),
                  Switch(
                    value: _isAvailable,
                    onChanged: (v) => setState(() => _isAvailable = v),
                    activeColor: AppColors.primary,
                  ),
                ],
              ),
            ),

            const SizedBox(height: 24),

            // Save button
            SizedBox(
              width: double.infinity,
              height: 52,
              child: ElevatedButton(
                onPressed: _isSaving ? null : _save,
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50)),
                ),
                child: _isSaving
                  ? const SizedBox(width: 24, height: 24, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2.5))
                  : Text("Simpan Perubahan", style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 15)),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _inputField(TextEditingController ctrl, String hint, TextInputType type, {int maxLines = 1}) {
    return TextField(
      controller: ctrl,
      keyboardType: type,
      maxLines: maxLines,
      style: GoogleFonts.inter(fontWeight: FontWeight.w600, fontSize: 14),
      decoration: InputDecoration(
        hintText: hint,
        hintStyle: GoogleFonts.inter(color: Colors.grey[400], fontSize: 13),
        filled: true,
        fillColor: const Color(0xFFF8F8F8),
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: BorderSide.none),
        enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: BorderSide.none),
        focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: const BorderSide(color: AppColors.primary, width: 1.5)),
      ),
    );
  }
}

// ─────────────────────────────────────────────────────────────────
// Add Product Bottom Sheet
// ─────────────────────────────────────────────────────────────────
class _AddProductSheet extends StatefulWidget {
  final List<CategoryModel> categories;
  final VoidCallback onSaved;

  const _AddProductSheet({required this.categories, required this.onSaved});

  @override
  State<_AddProductSheet> createState() => _AddProductSheetState();
}

class _AddProductSheetState extends State<_AddProductSheet> {
  late TextEditingController _nameCtrl;
  late TextEditingController _priceCtrl;
  late TextEditingController _descCtrl;
  late bool _isAvailable;
  CategoryModel? _selectedCategory;
  File? _selectedImage;
  bool _isSaving = false;

  @override
  void initState() {
    super.initState();
    _nameCtrl = TextEditingController();
    _priceCtrl = TextEditingController();
    _descCtrl = TextEditingController();
    _isAvailable = true;
    if (widget.categories.isNotEmpty) {
      _selectedCategory = widget.categories.first;
    }
  }

  @override
  void dispose() {
    _nameCtrl.dispose();
    _priceCtrl.dispose();
    _descCtrl.dispose();
    super.dispose();
  }

  Future<void> _pickImage() async {
    final picker = ImagePicker();
    final pickedFile = await picker.pickImage(source: ImageSource.gallery);
    if (pickedFile != null) {
      setState(() => _selectedImage = File(pickedFile.path));
    }
  }

  Future<void> _save() async {
    if (_nameCtrl.text.trim().isEmpty || _priceCtrl.text.trim().isEmpty || _selectedCategory == null) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("Kategori, nama, dan harga wajib diisi!"), backgroundColor: Colors.red));
      return;
    }

    setState(() => _isSaving = true);
    try {
      final api = ApiService();
      
      if (_selectedImage != null) {
        final formData = FormData.fromMap({
          'category_id': _selectedCategory!.id,
          'name': _nameCtrl.text.trim(),
          'base_price': int.tryParse(_priceCtrl.text.trim()) ?? 0,
          'description': _descCtrl.text.trim(),
          'is_available': _isAvailable ? 1 : 0,
          'image': await MultipartFile.fromFile(_selectedImage!.path),
        });
        
        final res = await api.post('/seller/products', data: formData);
        
        if (!mounted) return;
        if (res.data['success'] == true) {
          Navigator.pop(context);
          ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("Produk baru berhasil ditambahkan!"), backgroundColor: Colors.green));
          widget.onSaved();
        } else {
          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(res.data['message'] ?? 'Gagal menyimpan'), backgroundColor: Colors.red));
        }
      } else {
        final res = await api.post('/seller/products', data: {
          'category_id': _selectedCategory!.id,
          'name': _nameCtrl.text.trim(),
          'base_price': int.tryParse(_priceCtrl.text.trim()) ?? 0,
          'description': _descCtrl.text.trim(),
          'is_available': _isAvailable ? 1 : 0,
        });

        if (!mounted) return;
        if (res.data['success'] == true) {
          Navigator.pop(context);
          ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("Produk baru berhasil ditambahkan!"), backgroundColor: Colors.green));
          widget.onSaved();
        } else {
          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(res.data['message'] ?? 'Gagal menyimpan'), backgroundColor: Colors.red));
        }
      }
    } catch (e) {
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text("Error: ${e.toString()}"), backgroundColor: Colors.red));
    } finally {
      if (mounted) setState(() => _isSaving = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    final bottomInset = MediaQuery.of(context).viewInsets.bottom;

    return Container(
      margin: const EdgeInsets.fromLTRB(12, 0, 12, 12),
      padding: EdgeInsets.fromLTRB(24, 24, 24, 24 + bottomInset),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(30)),
      child: SingleChildScrollView(
        child: Column(
          mainAxisSize: MainAxisSize.min,
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            // Handle bar
            Center(child: Container(width: 40, height: 4, decoration: BoxDecoration(color: Colors.grey[300], borderRadius: BorderRadius.circular(4)))),
            const SizedBox(height: 20),
            Text("Tambah Menu", style: GoogleFonts.inter(fontWeight: FontWeight.w900, fontSize: 20, color: const Color(0xFF1A1A1A))),
            Text("Silakan masukkan detail menu baru", style: GoogleFonts.inter(fontSize: 12, color: Colors.grey[500])),
            const SizedBox(height: 20),

            // Image Preview & Upload
            Center(
              child: Column(
                children: [
                  GestureDetector(
                    onTap: _pickImage,
                    child: Stack(
                      children: [
                        Container(
                          width: 100, height: 100,
                          decoration: BoxDecoration(
                            color: const Color(0xFFF8EFDE),
                            borderRadius: BorderRadius.circular(20),
                            border: Border.all(color: AppColors.primary.withAlpha((0.2 * 255).toInt()), width: 2),
                          ),
                          child: ClipRRect(
                            borderRadius: BorderRadius.circular(18),
                            child: _selectedImage != null
                              ? Image.file(_selectedImage!, fit: BoxFit.cover)
                              : Center(child: Icon(Icons.fastfood, size: 40, color: AppColors.primary.withAlpha((0.5 * 255).toInt()))),
                          ),
                        ),
                        Positioned(
                          right: 0, bottom: 0,
                          child: Container(
                            padding: const EdgeInsets.all(6),
                            decoration: const BoxDecoration(color: AppColors.primary, shape: BoxShape.circle),
                            child: const Icon(Icons.camera_alt, color: Colors.white, size: 16),
                          ),
                        ),
                      ],
                    ),
                  ),
                  const SizedBox(height: 8),
                  Text("Upload Foto Menu", style: GoogleFonts.inter(fontSize: 11, color: AppColors.primary, fontWeight: FontWeight.w700)),
                ],
              ),
            ),
            const SizedBox(height: 24),

            // Category field
            Text("Kategori", style: GoogleFonts.inter(fontWeight: FontWeight.w700, fontSize: 13, color: const Color(0xFF333333))),
            const SizedBox(height: 8),
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 16),
              decoration: BoxDecoration(
                color: const Color(0xFFF8F8F8),
                borderRadius: BorderRadius.circular(16),
              ),
              child: DropdownButtonHideUnderline(
                child: DropdownButton<CategoryModel>(
                  isExpanded: true,
                  value: _selectedCategory,
                  items: widget.categories.map((c) {
                    return DropdownMenuItem(
                      value: c,
                      child: Text(c.name, style: GoogleFonts.inter(fontWeight: FontWeight.w600, fontSize: 14)),
                    );
                  }).toList(),
                  onChanged: (c) {
                    setState(() => _selectedCategory = c);
                  },
                ),
              ),
            ),
            const SizedBox(height: 16),

            // Name field
            Text("Nama Menu", style: GoogleFonts.inter(fontWeight: FontWeight.w700, fontSize: 13, color: const Color(0xFF333333))),
            const SizedBox(height: 8),
            _inputField(_nameCtrl, "Nama produk", TextInputType.text),
            const SizedBox(height: 16),

            // Price field
            Text("Harga (Rp)", style: GoogleFonts.inter(fontWeight: FontWeight.w700, fontSize: 13, color: const Color(0xFF333333))),
            const SizedBox(height: 8),
            _inputField(_priceCtrl, "Contoh: 15000", TextInputType.number),
            const SizedBox(height: 16),

            // Description field
            Text("Deskripsi", style: GoogleFonts.inter(fontWeight: FontWeight.w700, fontSize: 13, color: const Color(0xFF333333))),
            const SizedBox(height: 8),
            _inputField(_descCtrl, "Deskripsi singkat menu", TextInputType.multiline, maxLines: 2),
            const SizedBox(height: 16),

            // Availability toggle
            Container(
              padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 12),
              decoration: BoxDecoration(color: const Color(0xFFF8EFDE), borderRadius: BorderRadius.circular(16)),
              child: Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Column(
                    crossAxisAlignment: CrossAxisAlignment.start,
                    children: [
                      Text("Status Menu", style: GoogleFonts.inter(fontWeight: FontWeight.w700, fontSize: 13)),
                      Text(_isAvailable ? "Tersedia untuk dipesan" : "Menu sedang ditahan/habis", style: GoogleFonts.inter(fontSize: 11, color: Colors.grey[600])),
                    ],
                  ),
                  Switch(
                    value: _isAvailable,
                    onChanged: (v) => setState(() => _isAvailable = v),
                    activeColor: AppColors.primary,
                  ),
                ],
              ),
            ),

            const SizedBox(height: 24),

            // Save button
            SizedBox(
              width: double.infinity,
              height: 52,
              child: ElevatedButton(
                onPressed: _isSaving ? null : _save,
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50)),
                ),
                child: _isSaving
                  ? const SizedBox(width: 24, height: 24, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 2.5))
                  : Text("Tambahkan Menu", style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 15)),
              ),
            ),
          ],
        ),
      ),
    );
  }

  Widget _inputField(TextEditingController ctrl, String hint, TextInputType type, {int maxLines = 1}) {
    return TextField(
      controller: ctrl,
      keyboardType: type,
      maxLines: maxLines,
      style: GoogleFonts.inter(fontWeight: FontWeight.w600, fontSize: 14),
      decoration: InputDecoration(
        hintText: hint,
        hintStyle: GoogleFonts.inter(color: Colors.grey[400], fontSize: 13),
        filled: true,
        fillColor: const Color(0xFFF8F8F8),
        contentPadding: const EdgeInsets.symmetric(horizontal: 16, vertical: 14),
        border: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: BorderSide.none),
        enabledBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: BorderSide.none),
        focusedBorder: OutlineInputBorder(borderRadius: BorderRadius.circular(16), borderSide: const BorderSide(color: AppColors.primary, width: 1.5)),
      ),
    );
  }
}
