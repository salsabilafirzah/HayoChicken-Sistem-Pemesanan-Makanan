class AppConstants {
  // Gunakan 10.0.2.2 untuk akses localhost dari Android Emulator
  // Gunakan IP lokal jika akses dari perangkat fisik
  static const String baseUrl = "http://10.0.2.2:8000/api/v1";
  
  // Storage Keys
  static const String tokenKey = "access_token";
  static const String refreshTokenKey = "refresh_token";
  static const String userRoleKey = "user_role";
}
