import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import '../providers/cart_provider.dart';

class CartScreen extends ConsumerWidget {
  const CartScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final state = ref.watch(cartProvider);

    return Scaffold(
      appBar: AppBar(title: const Text("Keranjang Belanja")),
      body: state.isLoading 
        ? const Center(child: CircularProgressIndicator())
        : state.items.isEmpty
          ? const Center(child: Text("Keranjang masih kosong"))
          : Column(
              children: [
                Expanded(
                  child: ListView.builder(
                    itemCount: state.items.length,
                    itemBuilder: (context, index) {
                      final item = state.items[index];
                      return Padding(
                        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
                        child: Card(
                          child: Padding(
                            padding: const EdgeInsets.all(8.0),
                            child: Row(
                              children: [
                                Checkbox(
                                  value: item.isChecked,
                                  onChanged: (_) => ref.read(cartProvider.notifier).toggleCheck(item.id),
                                ),
                                Container(
                                  width: 60, height: 60,
                                  color: Colors.grey[200],
                                  child: const Icon(Icons.fastfood),
                                ),
                                const SizedBox(width: 12),
                                Expanded(
                                  child: Column(
                                    crossAxisAlignment: CrossAxisAlignment.start,
                                    children: [
                                      Text(item.product?.name ?? "Produk", style: const TextStyle(fontWeight: FontWeight.bold)),
                                      if (item.selectedExtras.isNotEmpty)
                                        Text(item.selectedExtras.join(", "), style: const TextStyle(fontSize: 12, color: Colors.grey)),
                                      Text("Rp ${item.subtotal}", style: const TextStyle(color: Colors.red)),
                                    ],
                                  ),
                                ),
                                Row(
                                  children: [
                                    IconButton(
                                      icon: const Icon(Icons.remove_circle_outline, size: 20),
                                      onPressed: () => ref.read(cartProvider.notifier).updateQuantity(item.id, item.quantity - 1),
                                    ),
                                    Text("${item.quantity}"),
                                    IconButton(
                                      icon: const Icon(Icons.add_circle_outline, size: 20),
                                      onPressed: () => ref.read(cartProvider.notifier).updateQuantity(item.id, item.quantity + 1),
                                    ),
                                  ],
                                ),
                                IconButton(
                                  icon: const Icon(Icons.delete_outline, color: Colors.red),
                                  onPressed: () => ref.read(cartProvider.notifier).removeItem(item.id),
                                ),
                              ],
                            ),
                          ),
                        ),
                      );
                    },
                  ),
                ),
                Container(
                  padding: const EdgeInsets.all(24),
                  decoration: BoxDecoration(
                    color: Colors.white,
                    boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 10, offset: const Offset(0, -5))],
                  ),
                  child: SafeArea(
                    child: Row(
                      mainAxisAlignment: MainAxisAlignment.spaceBetween,
                      children: [
                        Column(
                          crossAxisAlignment: CrossAxisAlignment.start,
                          mainAxisSize: MainAxisSize.min,
                          children: [
                            const Text("Total Bayar", style: TextStyle(color: Colors.grey)),
                            Text("Rp ${state.totalAmount}", style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold, color: Colors.red)),
                          ],
                        ),
                        ElevatedButton(
                          onPressed: state.checkedCount == 0 ? null : () => context.push('/checkout'),
                          style: ElevatedButton.styleFrom(
                            backgroundColor: Colors.red,
                            padding: const EdgeInsets.symmetric(horizontal: 32, vertical: 16),
                          ),
                          child: const Text("CHECKOUT", style: TextStyle(color: Colors.white)),
                        ),
                      ],
                    ),
                  ),
                ),
              ],
            ),
    );
  }
}
