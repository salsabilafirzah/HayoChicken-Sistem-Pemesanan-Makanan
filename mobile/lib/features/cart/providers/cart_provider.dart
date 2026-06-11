import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/cart_model.dart';
import '../services/cart_service.dart';

class CartState {
  final List<CartItemModel> items;
  final bool isLoading;
  final bool isUpdating; // New flag for minor updates

  CartState({this.items = const [], this.isLoading = false, this.isUpdating = false});

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
  int get totalCount => items.fold(0, (sum, i) => sum + i.quantity);

  CartState copyWith({List<CartItemModel>? items, bool? isLoading, bool? isUpdating}) {
    return CartState(
      items: items ?? this.items,
      isLoading: isLoading ?? this.isLoading,
      isUpdating: isUpdating ?? this.isUpdating,
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
    state = state.copyWith(isUpdating: true);
    final success = await _service.addToCart(
      productId: productId,
      quantity: quantity,
      extras: extras,
      note: note,
    );
    final items = await _service.getCart();
    state = state.copyWith(items: items, isUpdating: false);
  }

  Future<void> toggleCheck(int itemId) async {
    // Optimistic Update
    final newItems = state.items.map<CartItemModel>((i) {
      if (i.id == itemId) {
        return i.copyWith(isChecked: !i.isChecked);
      }
      return i;
    }).toList();
    state = state.copyWith(items: newItems, isUpdating: true);

    final success = await _service.toggleCheck(itemId);
    if (!success) {
      // Rollback if failed
      final items = await _service.getCart();
      state = state.copyWith(items: items);
    }
    state = state.copyWith(isUpdating: false);
  }

  Future<void> updateQuantity(int itemId, int newQty) async {
    if (newQty < 1) {
      await removeItem(itemId);
      return;
    }

    // UPDATE UI INSTANTLY
    final updatedItems = state.items.map<CartItemModel>((i) {
      if (i.id == itemId) return i.copyWith(quantity: newQty);
      return i;
    }).toList();
    
    state = state.copyWith(items: updatedItems);

    // SERVER CALL IN BACKGROUND
    await _service.updateQuantity(itemId, newQty);
    
    // We don't rollback here anymore to keep UI stable for the user
  }

  Future<void> removeItem(int itemId) async {
    state = state.copyWith(isUpdating: true);
    final success = await _service.deleteItem(itemId);
    if (success) {
      final items = await _service.getCart();
      state = state.copyWith(items: items);
    }
    state = state.copyWith(isUpdating: false);
  }

  Future<void> removeCheckedItems() async {
    final checkedItems = state.items.where((i) => i.isChecked).toList();
    if (checkedItems.isEmpty) return;

    state = state.copyWith(isUpdating: true);
    for (var item in checkedItems) {
      await _service.deleteItem(item.id);
    }
    final items = await _service.getCart();
    state = state.copyWith(items: items, isUpdating: false);
  }

  Future<void> toggleSelectAll() async {
    final allChecked = state.items.every((i) => i.isChecked);
    final newState = !allChecked;
    
    state = state.copyWith(isUpdating: true);
    
    // Optimistic UI update
    final updatedItems = state.items.map((i) => i.copyWith(isChecked: newState)).toList();
    state = state.copyWith(items: updatedItems);

    for (var item in state.items) {
      if (item.isChecked != newState) {
        await _service.toggleCheck(item.id);
      }
    }
    
    // Final sync
    final items = await _service.getCart();
    state = state.copyWith(items: items, isUpdating: false);
  }
}

final cartProvider = StateNotifierProvider<CartNotifier, CartState>((ref) {
  return CartNotifier();
});
