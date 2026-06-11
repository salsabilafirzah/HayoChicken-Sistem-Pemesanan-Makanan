import 'package:dio/dio.dart';
import '../../../core/network/api_service.dart';
import '../models/product_model.dart';

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

  Future<Map<String, dynamic>> getProducts({
    int? categoryId,
    String? search,
    int page = 1,
  }) async {
    try {
      final response = await _api.get('/products', queryParameters: {
        if (categoryId != null) 'category_id': categoryId,
        if (search != null) 'search': search,
        'page': page,
      });
      
      final List productsJson = response.data['data']['data'];
      return {
        'products': productsJson.map((e) => ProductModel.fromJson(e)).toList(),
        'last_page': response.data['data']['last_page'],
      };
    } catch (e) {
      return {'products': <ProductModel>[], 'last_page': 1};
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
}
