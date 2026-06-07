import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../services/seller_order_service.dart';

class SellerOrderState {
  final List<dynamic> orders;
  final bool isLoading;
  final String currentFilter;

  SellerOrderState({
    this.orders = const [],
    this.isLoading = false,
    this.currentFilter = 'ALL',
  });

  SellerOrderState copyWith({
    List<dynamic>? orders,
    bool? isLoading,
    String? currentFilter,
  }) {
    return SellerOrderState(
      orders: orders ?? this.orders,
      isLoading: isLoading ?? this.isLoading,
      currentFilter: currentFilter ?? this.currentFilter,
    );
  }
}

class SellerOrderNotifier extends StateNotifier<SellerOrderState> {
  final SellerOrderService _service = SellerOrderService();

  SellerOrderNotifier() : super(SellerOrderState()) {
    refreshOrders();
  }

  Future<void> refreshOrders() async {
    state = state.copyWith(isLoading: true);
    final orders = await _service.getOrders(status: state.currentFilter);
    state = state.copyWith(orders: orders, isLoading: false);
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

final sellerOrderProvider = StateNotifierProvider<SellerOrderNotifier, SellerOrderState>((ref) {
  return SellerOrderNotifier();
});
