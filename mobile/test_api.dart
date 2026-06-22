import 'dart:convert';
import 'package:dio/dio.dart';

void main() async {
  final dio = Dio();
  try {
    final response = await dio.get('http://127.0.0.1:8000/api/v1/products');
    final List data = response.data['data']['data'];
    print("Products fetched: ${data.length}");
    
    for (var e in data) {
      try {
        final id = int.tryParse(e['id']?.toString() ?? '0') ?? 0;
        final name = e['name']?.toString() ?? '';
        final description = e['description']?.toString();
        final basePrice = int.tryParse(e['base_price']?.toString() ?? '0') ?? 0;
        final imageUrl = (e['product_image_url'] ?? e['image_url'])?.toString();
        final isAvailable = e['is_available'] == 1 || e['is_available'] == true || e['is_available'] == '1';
        final categoryId = int.tryParse(e['category_id']?.toString() ?? '0') ?? 0;
        
        final extras = (e['product_extras'] as List? ?? e['extras'] as List? ?? []);
        
        for (var ex in extras) {
             final exId = int.tryParse(ex['id']?.toString() ?? '0') ?? 0;
             final exName = ex['name']?.toString() ?? '';
             final additionalPrice = int.tryParse(ex['additional_price']?.toString() ?? '0') ?? 0;
        }

      } catch (err, stack) {
        print("Error parsing product: $err\n$stack");
        return;
      }
    }
    
    print("ALL products parsed successfully!");
  } catch (e, stack) {
    print("Outer error: $e\n$stack");
  }
}
