import 'package:flutter/material.dart';

class AppColors {
  static const Color primary = Color(0xFFD32F2F); // Red
  static const Color accent = Color(0xFFFFC107);  // Amber/Chicken Gold
  static const Color background = Color(0xFFF5F5F5);
  static const Color surface = Colors.white;
  static const Color textPrimary = Color(0xFF212121);
  static const Color textSecondary = Color(0xFF757575);
}

class AppTheme {
  static ThemeData get light {
    return ThemeData(
      useMaterial3: true,
      colorScheme: ColorScheme.fromSeed(
        seedColor: AppColors.primary,
        primary: AppColors.primary,
        secondary: AppColors.accent,
      ),
      scaffoldBackgroundColor: AppColors.background,
      fontFamily: 'Roboto', // Default modern font
    );
  }
}
