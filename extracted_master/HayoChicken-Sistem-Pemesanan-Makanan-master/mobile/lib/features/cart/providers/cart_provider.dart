import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/cart_model.dart';
import '../services/cart_service.dart';

class CartState {
  final List<CartItemModel> items;
  final bool isLoading;

  CartState({this.items = const [], this.isLoading = false});

  int get totalAmount {
    int total = 0;
    for (var item in items) {
      if (item.isChecked) {
        total += item.subtotal;
      }
    }
    return total;
  }

  int get checkedCount => items.where((i) => i.isChecked).length;

  CartState copyWith({List<CartItemModel>? items, bool? isLoading}) {
    return CartState(
      items: items ?? this.items,
      isLoading: isLoading ?? this.isLoading,
    );
  }
}

class CartNotifier extends StateNotifier<CartState> {
  final CartService _service = CartService();

  CartNotifier() : super(CartState()) {
    refreshCart();
  }

  Future<void> refreshCart() async {
    state = state.copyWith(isLoading: true);
    final items = await _service.getCart();
    state = state.copyWith(items: items, isLoading: false);
  }

  Future<void> addToCart({
    required int productId,
    required int quantity,
    required List<String> extras,
    String? note,
  }) async {
    final success = await _service.addToCart(
      productId: productId,
      quantity: quantity,
      extras: extras,
      note: note,
    );
    if (success) await refreshCart();
  }

  Future<void> toggleCheck(int itemId) async {
    final success = await _service.toggleCheck(itemId);
    if (success) await refreshCart();
  }

  Future<void> updateQuantity(int itemId, int quantity) async {
    if (quantity < 1) return;
    final success = await _service.updateQuantity(itemId, quantity);
    if (success) await refreshCart();
  }

  Future<void> removeItem(int itemId) async {
    final success = await _service.deleteItem(itemId);
    if (success) await refreshCart();
  }
}

final cartProvider = StateNotifierProvider<CartNotifier, CartState>((ref) {
  return CartNotifier();
});
