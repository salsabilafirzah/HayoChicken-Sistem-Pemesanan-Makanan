import 'package:dio/dio.dart';
import '../../../core/network/api_service.dart';

class SellerProductService {
  final ApiService _api = ApiService();

  Future<bool> createProduct(Map<String, dynamic> data, dynamic imageFile) async {
    try {
      final formData = FormData.fromMap({
        ...data,
        if (imageFile != null) 'image': imageFile,
      });
      await _api.dio.post('/products', data: formData);
      return true;
    } catch (e) {
      return false;
    }
  }

  Future<bool> updateProduct(int id, Map<String, dynamic> data, dynamic imageFile) async {
    try {
      final formData = FormData.fromMap({
        ...data,
        '_method': 'PUT', // Spoofing method untuk multipart di Laravel
        if (imageFile != null) 'image': imageFile,
      });
      await _api.dio.post('/products/$id', data: formData);
      return true;
    } catch (e) {
      return false;
    }
  }

  Future<bool> deleteProduct(int id) async {
    try {
      await _api.delete('/products/$id');
      return true;
    } catch (e) {
      return false;
    }
  }
}
