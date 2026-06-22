import 'package:dio/dio.dart';
import '../../../core/network/api_service.dart';
import '../models/user_model.dart';
import '../../../core/constants/constants.dart';
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class AuthService {
  final ApiService _api = ApiService();
  final _storage = const FlutterSecureStorage();

  Future<Map<String, dynamic>> login(String email, String password) async {
    try {
      final response = await _api.post('/auth/login', data: {
        'email': email,
        'password': password,
      });

      if (response.statusCode == 200) {
        final data = response.data;
        final user = UserModel.fromJson(data['user']);
        await _storage.write(key: AppConstants.tokenKey, value: data['access_token']);
        await _storage.write(key: AppConstants.refreshTokenKey, value: data['refresh_token']);
        await _storage.write(key: AppConstants.userRoleKey, value: user.role);
        ApiService.setToken(data['access_token']);
        return {'success': true, 'user': user};
      }
      return {'success': false, 'message': 'Login gagal'};
    } on DioException catch (e) {
      return {'success': false, 'message': e.response?.data['message'] ?? 'Error: ${e.message}'};
    } catch (e) {
      return {'success': false, 'message': 'Unknown Error: $e'};
    }
  }

  Future<Map<String, dynamic>> register({
    required String name,
    required String email,
    required String phone,
    required String password,
    required String passwordConfirm,
  }) async {
    try {
      final response = await _api.post('/auth/register', data: {
        'name': name,
        'email': email,
        'phone': phone,
        'password': password,
        'password_confirmation': passwordConfirm,
      });

      if (response.statusCode == 201) {
        return {'success': true, 'message': 'Registrasi berhasil'};
      }
      return {'success': false, 'message': 'Registrasi gagal'};
    } on DioException catch (e) {
      return {'success': false, 'message': e.response?.data['message'] ?? 'Error: ${e.message}'};
    } catch (e) {
      return {'success': false, 'message': 'Unknown Error: $e'};
    }
  }

  Future<Map<String, dynamic>> fetchMe() async {
    try {
      final response = await _api.get('/auth/me');
      if (response.statusCode == 200) {
        return {'success': true, 'user': UserModel.fromJson(response.data['user'])};
      }
      return {'success': false};
    } catch (e) {
      return {'success': false};
    }
  }

  Future<void> logout() async {
    final refreshToken = await _storage.read(key: AppConstants.refreshTokenKey);
    await _api.post('/auth/logout', data: {'refresh_token': refreshToken});
    await _storage.deleteAll();
  }

  Future<Map<String, dynamic>> updateProfile({required String name, required String phone}) async {
    try {
      final response = await _api.patch('/auth/profile', data: {
        'name': name,
        'phone': phone,
      });

      if (response.statusCode == 200) {
        return {'success': true, 'message': 'Profil diperbarui'};
      }
      return {'success': false, 'message': 'Gagal memperbarui profil'};
    } on DioException catch (e) {
      return {'success': false, 'message': e.response?.data['message'] ?? 'Gagal menghubungi server'};
    }
  }

  Future<Map<String, dynamic>> updatePassword({
    required String oldPassword,
    required String newPassword,
    required String newPasswordConfirm,
  }) async {
    try {
      final response = await _api.patch('/auth/password', data: {
        'old_password': oldPassword,
        'new_password': newPassword,
        'new_password_confirmation': newPasswordConfirm,
      });
      return {'success': true, 'message': response.data['message'] ?? 'Password berhasil diupdate'};
    } on DioException catch (e) {
      return {'success': false, 'message': e.response?.data['message'] ?? 'Gagal mengubah password'};
    }
  }
}
