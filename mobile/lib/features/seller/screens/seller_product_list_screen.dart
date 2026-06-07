import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../../catalog/providers/product_provider.dart';
import '../services/seller_product_service.dart';

class SellerProductListScreen extends ConsumerWidget {
  const SellerProductListScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final state = ref.watch(productProvider);

    return Scaffold(
      appBar: AppBar(
        title: const Text("Kelola Menu"),
        actions: [
          IconButton(
            icon: const Icon(Icons.add_circle, color: Colors.red),
            onPressed: () => _showAddEditDialog(context),
          ),
        ],
      ),
      body: state.isLoading 
        ? const Center(child: CircularProgressIndicator())
        : ListView.builder(
            itemCount: state.products.length,
            itemBuilder: (context, index) {
              final product = state.products[index];
              return ListTile(
                leading: product.imageUrl != null 
                  ? Image.network(product.imageUrl!, width: 50, height: 50, fit: BoxFit.cover)
                  : const Icon(Icons.fastfood),
                title: Text(product.name),
                subtitle: Text("Rp ${product.basePrice}"),
                trailing: Row(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Switch(
                      value: product.isAvailable,
                      onChanged: (v) {
                        // Toggle ketersediaan stok API logic
                      },
                    ),
                    IconButton(
                      icon: const Icon(Icons.edit, color: Colors.blue),
                      onPressed: () => _showAddEditDialog(context, product: product),
                    ),
                    IconButton(
                      icon: const Icon(Icons.delete, color: Colors.red),
                      onPressed: () => _handleDelete(context, product.id, ref),
                    ),
                  ],
                ),
              );
            },
          ),
    );
  }

  void _showAddEditDialog(BuildContext context, {dynamic product}) {
    // Placeholder untuk UI form tambah/edit yang lebih kompleks nanti
    ScaffoldMessenger.of(context).showSnackBar(
      const SnackBar(content: Text("Fitur Form Produk (multipart) akan tampil di sini.")),
    );
  }

  void _handleDelete(BuildContext context, int id, WidgetRef ref) async {
    final confirm = await showDialog<bool>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text("Hapus Produk?"),
        content: const Text("Tindakan ini tidak dapat dibatalkan."),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context, false), child: const Text("Batal")),
          TextButton(onPressed: () => Navigator.pop(context, true), child: const Text("Hapus", style: TextStyle(color: Colors.red))),
        ],
      ),
    );

    if (confirm == true) {
      final success = await SellerProductService().deleteProduct(id);
      if (success) {
        ref.read(productProvider.notifier).fetchProducts();
      }
    }
  }
}
