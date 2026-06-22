import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../../core/theme/app_theme.dart';
import '../../auth/providers/auth_provider.dart';

class ChangePasswordScreen extends ConsumerStatefulWidget {
  const ChangePasswordScreen({super.key});

  @override
  ConsumerState<ChangePasswordScreen> createState() => _ChangePasswordScreenState();
}

class _ChangePasswordScreenState extends ConsumerState<ChangePasswordScreen> {
  final _oldPasswordController = TextEditingController();
  final _newPasswordController = TextEditingController();
  final _confirmPasswordController = TextEditingController();
  bool _obscureOld = true;
  bool _obscureNew = true;
  bool _obscureConfirm = true;
  int _passwordStrength = 0;

  @override
  void initState() {
    super.initState();
    _newPasswordController.addListener(_updateStrength);
  }

  void _updateStrength() {
    final password = _newPasswordController.text;
    if (password.isEmpty) {
      if (_passwordStrength != 0) setState(() => _passwordStrength = 0);
      return;
    }

    int strength = 0;
    if (password.length >= 8) strength += 1;
    if (password.contains(RegExp(r'[a-zA-Z]'))) strength += 1;
    if (password.contains(RegExp(r'[0-9]'))) strength += 1;
    if (password.contains(RegExp(r'[^a-zA-Z0-9]'))) strength += 1;

    if (strength == 0 && password.isNotEmpty) strength = 1;

    if (_passwordStrength != strength) {
      setState(() => _passwordStrength = strength);
    }
  }

  @override
  void dispose() {
    _newPasswordController.removeListener(_updateStrength);
    _oldPasswordController.dispose();
    _newPasswordController.dispose();
    _confirmPasswordController.dispose();
    super.dispose();
  }

  void _handleSave() async {
    final oldPass = _oldPasswordController.text;
    final newPass = _newPasswordController.text;
    final confirmPass = _confirmPasswordController.text;

    if (oldPass.isEmpty || newPass.isEmpty || confirmPass.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Semua kolom wajib diisi'), backgroundColor: Colors.red));
      return;
    }

    final success = await ref.read(authProvider.notifier).updatePassword(
      oldPassword: oldPass,
      newPassword: newPass,
      confirmPassword: confirmPass,
    );

    if (mounted) {
      if (success) {
        ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text('Password berhasil diperbarui!'), backgroundColor: Colors.green));
        Navigator.pop(context);
      } else {
        final err = ref.read(authProvider).errorMessage ?? 'Gagal memperbarui password';
        ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(err), backgroundColor: Colors.red));
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    final authState = ref.watch(authProvider);

    return Scaffold(
      backgroundColor: const Color(0xFFF8EFDE),
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
                    _input("Masukkan password lama", _oldPasswordController, _obscureOld, () => setState(() => _obscureOld = !_obscureOld)),
                    Align(alignment: Alignment.centerRight, child: Text("Lupa Password?", style: GoogleFonts.inter(color: AppColors.primary, fontWeight: FontWeight.w800, fontSize: 13))),
                    const SizedBox(height: 24),
                    _fieldLabel("Password Baru"),
                    _input("Minimal 8 karakter", _newPasswordController, _obscureNew, () => setState(() => _obscureNew = !_obscureNew)),
                    const SizedBox(height: 10),
                    _strengthBar(),
                    const SizedBox(height: 4),
                    Text("Gunakan huruf, angka, dan simbol untuk password yang kuat.", style: GoogleFonts.inter(color: Colors.grey, fontSize: 11)),
                    const SizedBox(height: 24),
                    _fieldLabel("Konfirmasi Password Baru"),
                    _input("Ulangi password baru", _confirmPasswordController, _obscureConfirm, () => setState(() => _obscureConfirm = !_obscureConfirm)),
                    const SizedBox(height: 80),
                  ],
                ),
              ),
            ],
          ),
          if (MediaQuery.of(context).viewInsets.bottom == 0)
            Positioned(
              bottom: 40, left: 24, right: 24, 
              child: SizedBox(
                width: double.infinity, height: 60, 
                child: ElevatedButton(
                  onPressed: authState.isLoading ? null : _handleSave, 
                  style: ElevatedButton.styleFrom(backgroundColor: AppColors.primary, shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50))), 
                  child: authState.isLoading 
                    ? const SizedBox(width: 24, height: 24, child: CircularProgressIndicator(color: Colors.white, strokeWidth: 3))
                    : Text("Simpan Password", style: GoogleFonts.inter(fontWeight: FontWeight.w900, color: Colors.white, fontSize: 16))
                )
              )
            ),
        ],
      ),
    );
  }

  Widget _fieldLabel(String t) {
    return Padding(padding: const EdgeInsets.only(bottom: 10), child: Text(t, style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 14, color: const Color(0xFF555555))));
  }

  Widget _input(String hint, TextEditingController controller, bool obscureText, VoidCallback onToggle) {
    return Container(
      margin: const EdgeInsets.only(bottom: 12),
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 4),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(30), border: Border.all(color: const Color(0xFFF5F5F5), width: 1.5)),
      child: TextField(
        controller: controller,
        obscureText: obscureText,
        decoration: InputDecoration(
          hintText: hint,
          filled: false,
          fillColor: Colors.white,
          border: InputBorder.none,
          focusedBorder: InputBorder.none,
          enabledBorder: InputBorder.none,
          hintStyle: GoogleFonts.inter(color: const Color(0xFFBBAA99), fontSize: 14),
          suffixIcon: GestureDetector(
            onTap: onToggle,
            child: Icon(obscureText ? Icons.visibility_off_outlined : Icons.visibility_outlined, color: const Color(0xFFBBAA99)),
          ),
        ),
      ),
    );
  }

  Widget _strengthBar() {
    Color barColor = const Color(0xFFEBE0D0); // Default/Empty
    if (_passwordStrength == 1) barColor = Colors.red;
    if (_passwordStrength == 2) barColor = Colors.orange;
    if (_passwordStrength == 3) barColor = Colors.amber;
    if (_passwordStrength == 4) barColor = Colors.green;

    return Row(
      children: List.generate(4, (i) => Expanded(
        child: AnimatedContainer(
          duration: const Duration(milliseconds: 300),
          height: 4, 
          margin: const EdgeInsets.only(right: 4), 
          decoration: BoxDecoration(
            color: i < _passwordStrength ? barColor : const Color(0xFFF5F5F5), 
            borderRadius: BorderRadius.circular(10)
          )
        )
      )),
    );
  }
}

