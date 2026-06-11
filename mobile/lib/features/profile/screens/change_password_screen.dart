import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../../core/theme/app_theme.dart';

class ChangePasswordScreen extends StatelessWidget {
  const ChangePasswordScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF9F4EB),
      body: Stack(
        children: [
          Column(
            children: [
              Container(
                padding: const EdgeInsets.fromLTRB(20, 60, 20, 24),
                decoration: const BoxDecoration(
                  color: AppColors.primary,
                  borderRadius: BorderRadius.only(bottomLeft: Radius.circular(35), bottomRight: Radius.circular(35)),
                ),
                child: Row(
                  children: [
                    GestureDetector(
                      onTap: () => Navigator.pop(context),
                      child: Container(
                        padding: const EdgeInsets.all(8),
                        decoration: BoxDecoration(color: Colors.white.withOpacity(0.2), shape: BoxShape.circle),
                        child: const Icon(Icons.chevron_left, color: Colors.white),
                      ),
                    ),
                    const SizedBox(width: 16),
                    Text("Ubah Password", style: GoogleFonts.inter(color: Colors.white, fontSize: 20, fontWeight: FontWeight.w900)),
                  ],
                ),
              ),
              Expanded(
                child: ListView(
                  padding: const EdgeInsets.all(24),
                  children: [
                    _fieldLabel("Password Saat Ini"),
                    _input("Masukkan password lama"),
                    Align(alignment: Alignment.centerRight, child: Text("Lupa Password?", style: GoogleFonts.inter(color: AppColors.primary, fontWeight: FontWeight.w800, fontSize: 13))),
                    const SizedBox(height: 24),
                    _fieldLabel("Password Baru"),
                    _input("Minimal 8 karakter"),
                    const SizedBox(height: 10),
                    _strengthBar(),
                    const SizedBox(height: 4),
                    Text("Gunakan huruf, angka, dan simbol untuk password yang kuat.", style: GoogleFonts.inter(color: Colors.grey, fontSize: 11)),
                    const SizedBox(height: 24),
                    _fieldLabel("Konfirmasi Password Baru"),
                    _input("Ulangi password baru"),
                  ],
                ),
              ),
            ],
          ),
          Positioned(bottom: 40, left: 24, right: 24, child: SizedBox(width: double.infinity, height: 60, child: ElevatedButton(onPressed: () {}, style: ElevatedButton.styleFrom(backgroundColor: AppColors.primary, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50))), child: Text("Simpan Password", style: GoogleFonts.inter(fontWeight: FontWeight.w900, color: Colors.white, fontSize: 16))))),
        ],
      ),
    );
  }

  Widget _fieldLabel(String t) {
    return Padding(padding: const EdgeInsets.only(bottom: 10), child: Text(t, style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 14, color: const Color(0xFF555555))));
  }

  Widget _input(String hint) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 4),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(18), border: Border.all(color: const Color(0xFFF5F5F5), width: 1.5)),
      child: TextField(
        decoration: InputDecoration(
          hintText: hint,
          border: InputBorder.none,
          hintStyle: GoogleFonts.inter(color: const Color(0xFFBBAA99), fontSize: 14),
          suffixIcon: const Icon(Icons.visibility_outlined, color: Color(0xFFBBAA99)),
        ),
        obscureText: true,
      ),
    );
  }

  Widget _strengthBar() {
    return Row(
      children: List.generate(4, (i) => Expanded(child: Container(height: 4, margin: const EdgeInsets.only(right: 4), decoration: BoxDecoration(color: i < 2 ? const Color(0xFFEBE0D0) : const Color(0xFFF5F5F5), borderRadius: BorderRadius.circular(10))))),
    );
  }
}
