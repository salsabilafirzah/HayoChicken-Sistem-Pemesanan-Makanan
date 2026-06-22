import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/cart_model.dart';
import '../services/cart_service.dart';

class CartState {
  final List<CartItemModel> items;
  final bool isLoading;
  final bool isUpdating;
  final String? error;

  CartState({this.items = const [], this.isLoading = false, this.isUpdating = false, this.error});

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

  CartState copyWith({List<CartItemModel>? items, bool? isLoading, bool? isUpdating, String? error, bool clearError = false}) {
    return CartState(
      items: items ?? this.items,
      isLoading: isLoading ?? this.isLoading,
      isUpdating: isUpdating ?? this.isUpdating,
      error: clearError ? null : (error ?? this.error),
    );
  }
}

class CartNotifier extends StateNotifier<CartState> {
  final CartService _service = CartService();

  CartNotifier() : super(CartState()) {
    refreshCart();
  }

  Future<void> refreshCart() async {
    state = state.copyWith(isLoading: true, clearError: true);
    try {
      final items = await _service.getCart();
      state = state.copyWith(items: items, isLoading: false);
    } catch (e, stack) {
      state = state.copyWith(isLoading: false, error: e.toString() + "\n" + stack.toString(), items: []);
    }
  }

  Future<void> addToCart({
    required int productId,
    required int quantity,
    required List<String> extras,
    String? note,
  }) async {
    state = state.copyWith(isUpdating: true, clearError: true);
    try {
      final success = await _service.addToCart(
        productId: productId,
        quantity: quantity,
        extras: extras,
        note: note,
      );
      if (!success) {
         state = state.copyWith(error: "Failure from _service.addToCart");
      }
      final items = await _service.getCart();
      state = state.copyWith(items: items, isUpdating: false);
    } catch (e, stack) {
      state = state.copyWith(isLoading: false, isUpdating: false, error: e.toString() + "\n" + stack.toString());
    }
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
    // Optimistic Update: Hapus sementara di UI agar terasa instan & tidak ngelag
    final oldItems = state.items;
    final updatedItems = state.items.where((i) => i.id != itemId).toList();
    state = state.copyWith(items: updatedItems, isUpdating: true);

    final success = await _service.deleteItem(itemId);
    if (!success) {
      // Rollback jika terjadi masalah jaringan
      state = state.copyWith(items: oldItems);
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
    
    // We need to iterate over the OLD items to know what needs to be toggled on the server
    final oldItems = state.items;

    // Optimistic UI update
    final updatedItems = state.items.map((i) => i.copyWith(isChecked: newState)).toList();
    state = state.copyWith(items: updatedItems);

    for (var item in oldItems) {
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
