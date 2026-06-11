import 'package:dio/dio.dart';
import '../../../core/network/api_service.dart';
import '../models/cart_model.dart';

class CartService {
  final ApiService _api = ApiService();

  Future<List<CartItemModel>> getCart() async {
    try {
      final response = await _api.get('/cart');
      final List data = response.data['data'];
      return data.map((e) => CartItemModel.fromJson(e)).toList();
    } catch (e) {
      return [];
    }
  }

  Future<bool> addToCart({
    required int productId,
    required int quantity,
    required List<String> extras,
    String? note,
  }) async {
    try {
      await _api.post('/cart', data: {
        'product_id': productId,
        'quantity': quantity,
        'selected_extras_snapshot': extras,
        'note': note,
      });
      return true;
    } catch (e) {
      return false;
    }
  }

  Future<bool> updateQuantity(int itemId, int quantity) async {
    try {
      await _api.patch('/cart/$itemId', data: {'quantity': quantity});
      return true;
    } catch (e) {
      return false;
    }
  }

  Future<bool> toggleCheck(int itemId) async {
    try {
      await _api.patch('/cart/$itemId/toggle-check');
      return true;
    } catch (e) {
      return false;
    }
  }

  Future<bool> deleteItem(int itemId) async {
    try {
      await _api.delete('/cart/$itemId');
      return true;
    } catch (e) {
      return false;
    }
  }
}
