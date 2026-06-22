import 'dart:async';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../services/order_service.dart';

final activeOrdersProvider = FutureProvider<List<dynamic>>((ref) async {
  final timer = Timer(const Duration(seconds: 10), () {
    ref.invalidateSelf();
  });
  ref.onDispose(() => timer.cancel());

  final orders = await OrderService().getMyOrders();
  // Filter only active orders
  return orders.where((order) {
    final status = order['status'];
    return status != 'DONE' && status != 'REJECTED';
  }).toList();
});

final allOrdersProvider = FutureProvider<List<dynamic>>((ref) async {
  final timer = Timer(const Duration(seconds: 10), () {
    ref.invalidateSelf();
  });
  ref.onDispose(() => timer.cancel());

  return await OrderService().getMyOrders();
});
