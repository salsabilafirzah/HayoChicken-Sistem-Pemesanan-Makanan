import 'package:dio/dio.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';
import '../constants/constants.dart';

class ApiService {
  final Dio _dio = Dio();
  final _storage = const FlutterSecureStorage();
  static String? _cachedToken;
  static void clearToken() {
    _cachedToken = null;
  }

  static void setToken(String token) {
    _cachedToken = token;
  }

  static Future<String?> getToken() async {
    if (_cachedToken != null) return _cachedToken;
    _cachedToken =
        await const FlutterSecureStorage().read(key: AppConstants.tokenKey);
    return _cachedToken;
  }

  ApiService() {
    _dio.options.baseUrl = AppConstants.baseUrl;
    _dio.options.connectTimeout = const Duration(seconds: 10);
    _dio.options.receiveTimeout = const Duration(seconds: 10);

    _dio.interceptors.add(InterceptorsWrapper(
      onRequest: (options, handler) async {
        final token = await getToken();
        if (token != null) {
          options.headers['Authorization'] = 'Bearer $token';
        }
        options.headers['Accept'] = 'application/json';
        return handler.next(options);
      },
      onError: (DioException e, handler) async {
        if (e.response?.statusCode == 401) {
          // Jangan loop jika yang 401 adalah rute refresh atau login
          final path = e.requestOptions.path;
          if (path.contains('auth/refresh') || path.contains('auth/login')) {
            ApiService.clearToken();
            await const FlutterSecureStorage()
                .delete(key: AppConstants.tokenKey);
            return handler.next(e);
          }

          try {
            // 1. Coba Minta Token Baru (Refresh) pakai instance baru biar ngga nabrak interceptor
            final dioRefresh = Dio(BaseOptions(baseUrl: AppConstants.baseUrl));
            final oldToken = await getToken();

            final refreshResponse = await dioRefresh.post(
              '/auth/refresh',
              options: Options(headers: {
                'Authorization': 'Bearer $oldToken',
                'Accept': 'application/json',
              }),
            );

            if (refreshResponse.statusCode == 200 &&
                refreshResponse.data['access_token'] != null) {
              // 2. Simpan token baru
              final newToken = refreshResponse.data['access_token'];
              await const FlutterSecureStorage()
                  .write(key: AppConstants.tokenKey, value: newToken);
              ApiService.setToken(newToken);

              // 3. Ulangi request yang tadi gagal pakai token baru
              final opts = e.requestOptions;
              opts.headers['Authorization'] = 'Bearer $newToken';

              final cloneReq = await dioRefresh.request(
                opts.path,
                options: Options(
                  method: opts.method,
                  headers: opts.headers,
                ),
                data: opts.data,
                queryParameters: opts.queryParameters,
              );

              // 4. Resolve requestnya sedemikian rupa seolah-olah ngga pernah gagal
              return handler.resolve(cloneReq);
            }
          } catch (refreshErr) {
            // Jika refresh gagal (token terlampau basi), buang kunci dan force logout secara pasif
            ApiService.clearToken();
            await const FlutterSecureStorage()
                .delete(key: AppConstants.tokenKey);
            return handler.next(e);
          }
        }
        return handler.next(e);
      },
    ));
  }

  Dio get dio => _dio;

  // Helper methods
  Future<Response> get(String path, {Map<String, dynamic>? queryParameters}) {
    return _dio.get(path, queryParameters: queryParameters);
  }

  Future<Response> post(String path, {dynamic data}) {
    return _dio.post(path, data: data);
  }

  Future<Response> patch(String path, {dynamic data}) {
    return _dio.patch(path, data: data);
  }

  Future<Response> delete(String path) {
    return _dio.delete(path);
  }
}
