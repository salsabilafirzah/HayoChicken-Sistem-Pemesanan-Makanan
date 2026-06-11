import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import '../providers/product_provider.dart';
import '../../cart/providers/cart_provider.dart';
import '../models/product_model.dart';

class ProductDetailScreen extends ConsumerStatefulWidget {
  final int productId;
  const ProductDetailScreen({super.key, required this.productId});

  @override
  ConsumerState<ProductDetailScreen> createState() => _ProductDetailScreenState();
}

class _ProductDetailScreenState extends ConsumerState<ProductDetailScreen> {
  int _quantity = 1;
  final List<String> _selectedExtras = [];
  final TextEditingController _noteController = TextEditingController();
  ProductModel? _product;
  bool _isInitLoading = true;

  @override
  void initState() {
    super.initState();
    _loadProduct();
  }

  Future<void> _loadProduct() async {
    final product = await ref.read(productProvider.notifier)._service.getProductDetail(widget.productId);
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
    for (var extraName in _selectedExtras) {
      final extra = _product!.extras.firstWhere((e) => e.name == extraName);
      extrasTotal += extra.additionalPrice;
    }
    return (_product!.basePrice + extrasTotal) * _quantity;
  }

  void _handleAddToCart() async {
    if (_product == null) return;
    
    await ref.read(cartProvider.notifier).addToCart(
      productId: _product!.id,
      quantity: _quantity,
      extras: _selectedExtras,
      note: _noteController.text,
    );

    if (mounted) {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Berhasil ditambahkan ke keranjang!")),
      );
      context.pop();
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isInitLoading) return const Scaffold(body: Center(child: CircularProgressIndicator()));
    if (_product == null) return const Scaffold(body: Center(child: Text("Produk tidak ditemukan")));

    return Scaffold(
      appBar: AppBar(title: Text(_product!.name)),
      body: SingleChildScrollView(
        child: Column(
          children: [
            // Image Placeholder
            Container(
              height: 250,
              width: double.infinity,
              color: Colors.grey[200],
              child: _product!.imageUrl != null 
                ? Image.network(_product!.imageUrl!, fit: BoxFit.cover)
                : const Icon(Icons.image, size: 100),
            ),
            
            Padding(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      Text(_product!.name, style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold)),
                      Text("Rp ${_product!.basePrice}", style: const TextStyle(fontSize: 20, color: Colors.red)),
                    ],
                  ),
                  const SizedBox(height: 8),
                  Text(_product!.description ?? "Nikmati ayam goreng renyah bumbu rahasia."),
                  
                  const Divider(height: 32),
                  const Text("Tambahan (Extras)", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  ..._product!.extras.map((extra) => CheckboxListTile(
                    title: Text(extra.name),
                    secondary: Text("+Rp ${extra.additionalPrice}"),
                    value: _selectedExtras.contains(extra.name),
                    onChanged: (checked) {
                      setState(() {
                        if (checked!) {
                          _selectedExtras.add(extra.name);
                        } else {
                          _selectedExtras.remove(extra.name);
                        }
                      });
                    },
                  )),
                  
                  const Divider(height: 32),
                  const Text("Catatan", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  TextField(
                    controller: _noteController,
                    decoration: const InputDecoration(hintText: "Contoh: Paha bawah saja, jangan pedas"),
                  ),
                  
                  const SizedBox(height: 100), // Spacing for Bottom Bar
                ],
              ),
            ),
          ],
        ),
      ),
      bottomSheet: Container(
        padding: const EdgeInsets.all(16),
        decoration: BoxDecoration(
          color: Colors.white,
          boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.1), blurRadius: 10)],
        ),
        child: Row(
          children: [
            // Quantity Counter
            Row(
              children: [
                IconButton(onPressed: () => setState(() => _quantity > 1 ? _quantity-- : null), icon: const Icon(Icons.remove)),
                Text("$_quantity", style: const TextStyle(fontSize: 18)),
                IconButton(onPressed: () => setState(() => _quantity++), icon: const Icon(Icons.add)),
              ],
            ),
            const SizedBox(width: 16),
            Expanded(
              child: ElevatedButton(
                onPressed: _handleAddToCart,
                style: ElevatedButton.styleFrom(backgroundColor: Colors.red, padding: const EdgeInsets.symmetric(vertical: 16)),
                child: Text("TAMBAH - Rp $_totalPrice", style: const TextStyle(color: Colors.white)),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
