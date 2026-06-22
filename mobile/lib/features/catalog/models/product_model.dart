class ProductExtraModel {
  final int id;
  final String name;
  final int additionalPrice;

  ProductExtraModel({required this.id, required this.name, required this.additionalPrice});

  factory ProductExtraModel.fromJson(Map<dynamic, dynamic> jsonFallback) {
    final json = Map<String, dynamic>.from(jsonFallback);
    return ProductExtraModel(
      id: int.tryParse(json['id']?.toString() ?? '0') ?? 0,
      name: json['name']?.toString() ?? '',
      additionalPrice: int.tryParse(json['additional_price']?.toString() ?? '0') ?? 0,
    );
  }
}

class ProductModel {
  final int id;
  final String name;
  final String? description;
  final int basePrice;
  final String? imageUrl;
  final bool isAvailable;
  final int categoryId;
  final List<ProductExtraModel> extras;

  ProductModel({
    required this.id,
    required this.name,
    this.description,
    required this.basePrice,
    this.imageUrl,
    this.isAvailable = true,
    required this.categoryId,
    this.extras = const [],
  });

  factory ProductModel.fromJson(Map<String, dynamic> json) {
    return ProductModel(
      id: int.tryParse(json['id']?.toString() ?? '0') ?? 0,
      name: json['name']?.toString() ?? '',
      description: json['description']?.toString(),
      basePrice: int.tryParse(json['base_price']?.toString() ?? '0') ?? 0,
      imageUrl: (json['product_image_url'] ?? json['image_url'])?.toString(),
      isAvailable: json['is_available'] == 1 || json['is_available'] == true || json['is_available'] == '1',
      categoryId: int.tryParse(json['category_id']?.toString() ?? '0') ?? 0,
      extras: (json['product_extras'] as List? ?? json['extras'] as List? ?? [])
          .map((e) => ProductExtraModel.fromJson(Map<String, dynamic>.from(e as Map)))
          .toList(),
    );
  }
}
