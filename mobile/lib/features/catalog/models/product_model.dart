class ProductExtraModel {
  final int id;
  final String name;
  final int additionalPrice;

  ProductExtraModel({required this.id, required this.name, required this.additionalPrice});

  factory ProductExtraModel.fromJson(Map<String, dynamic> json) {
    return ProductExtraModel(
      id: json['id'],
      name: json['name'],
      additionalPrice: json['additional_price'] is String 
          ? int.parse(json['additional_price']) 
          : json['additional_price'],
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
      id: json['id'],
      name: json['name'],
      description: json['description'],
      basePrice: json['base_price'] is String 
          ? int.parse(json['base_price']) 
          : json['base_price'],
      imageUrl: json['product_image_url'],
      isAvailable: json['is_available'] == 1 || json['is_available'] == true,
      categoryId: json['category_id'],
      extras: (json['product_extras'] as List? ?? [])
          .map((e) => ProductExtraModel.fromJson(e))
          .toList(),
    );
  }
}
