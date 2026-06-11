import '../../../core/network/api_service.dart';

class AnalyticsService {
  final ApiService _api = ApiService();

  Future<Map<String, dynamic>> getSummary() async {
    try {
      final response = await _api.get('/seller/analytics/summary');
      return response.data;
    } catch (e) {
      return {};
    }
  }
}
