import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import '../services/auth_service.dart';
import '../../../core/theme/app_theme.dart';

class RegisterScreen extends ConsumerStatefulWidget {
  const RegisterScreen({super.key});

  @override
  ConsumerState<RegisterScreen> createState() => _RegisterScreenState();
}

class _RegisterScreenState extends ConsumerState<RegisterScreen> {
  final _nameController = TextEditingController();
  final _phoneController = TextEditingController();
  final _emailController = TextEditingController();
  final _passwordController = TextEditingController();
  final _confirmController = TextEditingController();
  final _formKey = GlobalKey<FormState>();
  bool _isLoading = false;
  bool _isPasswordVisible = false;
  bool _isConfirmVisible = false;

  void _handleRegister() async {
    if (_formKey.currentState!.validate()) {
      setState(() => _isLoading = true);
      final result = await AuthService().register(
        name: _nameController.text,
        email: _emailController.text,
        phone: _phoneController.text,
        password: _passwordController.text,
        passwordConfirm: _confirmController.text,
      );
      setState(() => _isLoading = false);

      if (result['success']) {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text("Registrasi berhasil! Silakan login", style: GoogleFonts.inter())));
          context.go('/login');
        }
      } else {
        if (mounted) {
          ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(result['message'], style: GoogleFonts.inter())));
        }
      }
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppColors.background,
      body: SingleChildScrollView(
        child: Column(
          children: [
            // Header Curved
            Container(
              width: double.infinity,
              padding: const EdgeInsets.only(top: 80, bottom: 40, left: 24, right: 24),
              decoration: const BoxDecoration(
                color: AppColors.primary,
                borderRadius: BorderRadius.only(
                  bottomLeft: Radius.circular(50),
                  bottomRight: Radius.circular(50),
                ),
              ),
              child: Column(
                children: [
                  Row(
                    mainAxisAlignment: MainAxisAlignment.spaceBetween,
                    children: [
                      GestureDetector(
                        onTap: () => context.pop(),
                        child: Container(
                          padding: const EdgeInsets.all(8),
                          decoration: BoxDecoration(color: Colors.white.withOpacity(0.2), shape: BoxShape.circle),
                          child: const Icon(Icons.chevron_left, color: Colors.white),
                        ),
                      ),
                      Text(
                        "Buat Akun",
                        style: GoogleFonts.inter(color: Colors.white, fontSize: 28, fontWeight: FontWeight.w900),
                      ),
                      const SizedBox(width: 40), // Spacer
                    ],
                  ),
                  const SizedBox(height: 8),
                  Text(
                    "Daftar dan mulai order sekarang!",
                    style: GoogleFonts.inter(color: Colors.white.withOpacity(0.85), fontSize: 14, fontWeight: FontWeight.w500),
                  ),
                ],
              ),
            ),

            // Form Area
            Padding(
              padding: const EdgeInsets.all(32.0),
              child: Form(
                key: _formKey,
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text("Nama Lengkap", style: GoogleFonts.inter(fontWeight: FontWeight.bold, fontSize: 14)),
                    const SizedBox(height: 8),
                    TextFormField(
                      controller: _nameController,
                      decoration: const InputDecoration(hintText: "Nama kamu"),
                      validator: (v) => v!.isEmpty ? "Nama wajib diisi" : null,
                    ),
                    const SizedBox(height: 20),
                    Text("Nomor HP", style: GoogleFonts.inter(fontWeight: FontWeight.bold, fontSize: 14)),
                    const SizedBox(height: 8),
                    TextFormField(
                      controller: _phoneController,
                      keyboardType: TextInputType.phone,
                      decoration: const InputDecoration(hintText: "08xxxxxxxxxxxx"),
                      validator: (v) => v!.isEmpty ? "Nomor HP wajib diisi" : null,
                    ),
                    const SizedBox(height: 20),
                    Text("Email", style: GoogleFonts.inter(fontWeight: FontWeight.bold, fontSize: 14)),
                    const SizedBox(height: 8),
                    TextFormField(
                      controller: _emailController,
                      keyboardType: TextInputType.emailAddress,
                      decoration: const InputDecoration(hintText: "email@gmail.com"),
                      validator: (v) => v!.isEmpty ? "Email wajib diisi" : null,
                    ),
                    const SizedBox(height: 20),
                    Text("Password", style: GoogleFonts.inter(fontWeight: FontWeight.bold, fontSize: 14)),
                    const SizedBox(height: 8),
                    TextFormField(
                      controller: _passwordController,
                      obscureText: !_isPasswordVisible,
                      decoration: InputDecoration(
                        hintText: "Min 8 karakter",
                        suffixIcon: IconButton(
                          icon: Icon(_isPasswordVisible ? Icons.visibility : Icons.visibility_off, color: Colors.grey),
                          onPressed: () => setState(() => _isPasswordVisible = !_isPasswordVisible),
                        ),
                      ),
                      validator: (v) => v!.length < 8 ? "Minimal 8 karakter" : null,
                    ),
                    const SizedBox(height: 20),
                    Text("Konfirmasi Password", style: GoogleFonts.inter(fontWeight: FontWeight.bold, fontSize: 14)),
                    const SizedBox(height: 8),
                    TextFormField(
                      controller: _confirmController,
                      obscureText: !_isConfirmVisible,
                      decoration: InputDecoration(
                        hintText: "Ketik ulang password",
                        suffixIcon: IconButton(
                          icon: Icon(_isConfirmVisible ? Icons.visibility : Icons.visibility_off, color: Colors.grey),
                          onPressed: () => setState(() => _isConfirmVisible = !_isConfirmVisible),
                        ),
                      ),
                      validator: (v) {
                        if (v!.isEmpty) return "Wajib diisi";
                        if (v != _passwordController.text) return "Password tidak cocok";
                        return null;
                      },
                    ),
                    const SizedBox(height: 32),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton(
                        onPressed: _isLoading ? null : _handleRegister,
                        child: _isLoading 
                          ? const CircularProgressIndicator(color: Colors.white)
                          : const Text("Daftar Sekarang"),
                      ),
                    ),
                    const SizedBox(height: 24),
                    Center(
                      child: Row(
                        mainAxisSize: MainAxisSize.min,
                        children: [
                          Text("Sudah punya akun? ", style: GoogleFonts.inter(color: Colors.grey[600])),
                          GestureDetector(
                            onTap: () => context.go('/login'),
                            child: Text(
                              "Masuk",
                              style: GoogleFonts.inter(color: AppColors.primary, fontWeight: FontWeight.bold),
                            ),
                          ),
                        ],
                      ),
                    ),
                    const SizedBox(height: 40),
                  ],
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
