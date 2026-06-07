import 'package:dio/dio.dart';
import '../../../core/network/api_service.dart';

class SellerOrderService {
  final ApiService _api = ApiService();

  Future<List<dynamic>> getOrders({String? status}) async {
    try {
      final response = await _api.get('/seller/orders', queryParameters: {
        if (status != null && status != 'ALL') 'status': status,
      });
      return response.data['data'];
    } catch (e) {
      return [];
    }
  }

  Future<Map<String, dynamic>> updateStatus(int orderId, String status, {String? note}) async {
    try {
      final response = await _api.patch('/seller/orders/$orderId/status', data: {
        'status': status,
        'note': note,
      });
      return {'success': true, 'message': response.data['message']};
    } on DioException catch (e) {
      return {
        'success': false, 
        'message': e.response?.data['message'] ?? 'Gagal memperbarui status'
      };
    }
  }
}
