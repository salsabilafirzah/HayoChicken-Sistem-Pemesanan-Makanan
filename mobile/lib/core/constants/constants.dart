import 'dart:io' show Platform;
import 'package:flutter/foundation.dart' show kIsWeb;

class AppConstants {
  static String get baseUrl {
    // KEMBALIKAN KE IP WIFI: Ternyata pengguna menggunakan Physical Device (HP Asli), bukan Android Emulator.
    // HP Asli tidak paham IP 10.0.2.2 (hanya emulator yang paham).
    return 'http://192.168.0.113:8000/api/v1';
  }
  
  static const String tokenKey = 'auth_token';
  static const String refreshTokenKey = 'refresh_token';
  static const String userRoleKey = 'user_role';
}
