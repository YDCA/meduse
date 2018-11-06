<?php
class Product extends ProductCore {

  public function __construct($id_product = null, $full = false, $id_lang = null, $id_shop = null, Context $context = null)
  {
      parent::__construct($id_product, $id_lang, $id_shop);
      if ($full && $this->id) {
          if (!$context) {
              $context = Context::getContext();
          }

          $this->isFullyLoaded = $full;
          $this->tax_name = 'deprecated'; // The applicable tax may be BOTH the product one AND the state one (moreover this variable is some deadcode)
          $this->manufacturer_name = Manufacturer::getNameById((int)$this->id_manufacturer);
          $this->supplier_name = Supplier::getNameById((int)$this->id_supplier);
          $this->defcat_name = Product::defCat((int)$this->id_category_default); // HERE IS CUSTOM VARIABLE FOR TPL
          $address = null;
          if (is_object($context->cart) && $context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')} != null) {
              $address = $context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')};
          }

          $this->tax_rate = $this->getTaxesRate(new Address($address));

          $this->new = $this->isNew();

          // Keep base price
          $this->base_price = $this->price;

          $this->price = Product::getPriceStatic((int)$this->id, false, null, 6, null, false, true, 1, false, null, null, null, $this->specificPrice);
          $this->unit_price = ($this->unit_price_ratio != 0  ? $this->price / $this->unit_price_ratio : 0);
          if ($this->id) {
              $this->tags = Tag::getProductTags((int)$this->id);
          }

          $this->loadStockData();
      }

      if ($this->id_category_default) {
          $this->category = Category::getLinkRewrite((int)$this->id_category_default, (int)$id_lang);
      }
  }

  public function defCat($id_category_default)
	{
				$category = new Category($id_category_default);
				$defcat_name = $category->getName();
				 return $defcat_name;
	}
  
}
?>
