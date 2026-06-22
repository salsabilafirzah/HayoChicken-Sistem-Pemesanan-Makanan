import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../services/seller_order_service.dart';

class SellerOrderState {
  final List<dynamic> orders;
  final bool isLoading;
  final String currentFilter;
  final String? errorMessage;

  SellerOrderState({
    this.orders = const [],
    this.isLoading = false,
    this.currentFilter = 'ALL',
    this.errorMessage,
  });

  SellerOrderState copyWith({
    List<dynamic>? orders,
    bool? isLoading,
    String? currentFilter,
    String? errorMessage,
  }) {
    return SellerOrderState(
      orders: orders ?? this.orders,
      isLoading: isLoading ?? this.isLoading,
      currentFilter: currentFilter ?? this.currentFilter,
      errorMessage: errorMessage ?? this.errorMessage,
    );
  }
}

class SellerOrderNotifier extends StateNotifier<SellerOrderState> {
  final SellerOrderService _service = SellerOrderService();

  SellerOrderNotifier() : super(SellerOrderState()) {
    refreshOrders();
  }

  Future<void> refreshOrders() async {
    state = state.copyWith(isLoading: true, errorMessage: null);
    try {
      final orders = await _service.getOrders(status: state.currentFilter);
      state = state.copyWith(orders: orders, isLoading: false);
    } catch (e) {
      state = state.copyWith(orders: [], isLoading: false, errorMessage: e.toString());
    }
  }

  void setFilter(String status) {
    state = state.copyWith(currentFilter: status);
    refreshOrders();
  }

  Future<Map<String, dynamic>> updateStatus(int orderId, String status, {String? note}) async {
    final result = await _service.updateStatus(orderId, status, note: note);
    if (result['success']) {
      await refreshOrders();
    }
    return result;
  }
}

final sellerOrderProvider = StateNotifierProvider.autoDispose<SellerOrderNotifier, SellerOrderState>((ref) {
  return SellerOrderNotifier();
});
