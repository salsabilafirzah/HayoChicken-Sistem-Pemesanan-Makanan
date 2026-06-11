import 'package:dio/dio.dart';
import '../../../core/network/api_service.dart';
import '../models/product_model.dart';
import '../models/category_model.dart';

class ProductService {
  final ApiService _api = ApiService();

  Future<List<CategoryModel>> getCategories() async {
    try {
      final response = await _api.get('/categories');
      final List data = response.data['data'];
      return data.map((e) => CategoryModel.fromJson(e)).toList();
    } catch (e) {
      return [];
    }
  }

  Future<List<ProductModel>> getProducts() async {
    try {
      final response = await _api.get('/products');
      final List data = response.data['data']['data'];
      return data.map((e) => ProductModel.fromJson(e)).toList();
    } catch (e) {
      return [];
    }
  }

  Future<ProductModel?> getProductDetail(int id) async {
    try {
      final response = await _api.get('/products/$id');
      return ProductModel.fromJson(response.data['data']);
    } catch (e) {
      return null;
    }
  }

  // FAVORITE API INTEGRATION
  Future<List<int>> getFavorites() async {
    try {
      final response = await _api.get('/favorites');
      final List data = response.data['data'];
      return data.map((e) => int.parse(e['product_id'].toString())).toList();
    } catch (e) {
      return [];
    }
  }

  Future<bool> toggleFavorite(int productId) async {
    try {
      await _api.post('/favorites/toggle', data: {'product_id': productId});
      return true;
    } catch (e) {
      return false;
    }
  }
}
