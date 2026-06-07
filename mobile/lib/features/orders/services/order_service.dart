import 'package:dio/dio.dart';
import '../../../core/network/api_service.dart';

class OrderService {
  final ApiService _api = ApiService();

  Future<Map<String, dynamic>> createOrder({
    required String address,
    required String paymentMethod,
    dynamic receiptFile, // MultipartFile?
  }) async {
    try {
      final formData = FormData.fromMap({
        'delivery_address': address,
        'payment_method': paymentMethod,
        if (receiptFile != null) 'payment_receipt': receiptFile,
      });

      final response = await _api.dio.post('/orders', data: formData);
      return {'success': true, 'data': response.data};
    } on DioException catch (e) {
      return {'success': false, 'message': e.response?.data['message'] ?? 'Checkout gagal'};
    }
  }

  Future<List<dynamic>> getMyOrders() async {
    try {
      final response = await _api.get('/orders');
      return response.data['data'];
    } catch (e) {
      return [];
    }
  }
}
