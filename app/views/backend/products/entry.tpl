<div class="view-third">
  <div class="nano">
    <div class="nano-content">
      <div class="column-title cl-f c-pdg-h c-pdg-t">
        <h3 class="f-ttl c-blk fl-l">
          {{#isNew}}
          Add product
          {{/isNew}}
          {{^isNew}}
          Edit Product
          {{/isNew}}
        </h3>
      </div><!-- /.column-title -->
      <form class="form c-pdg-h c-pdg-bas-t c-pdg-b" id="subbly-product-entry">
        <div class="form-row">
          <div class="form-field input-8">
            <label class="form-label">
              Title
            </label>
            <input type="text" class="form-input" name="name" value="{{name}}">
          </div><!-- /.form-field -->
          <div class="form-field input-4">
            <label class="form-label">
              Reference
            </label>
            <input type="text" class="form-input" name="sku" value="{{sku}}">
          </div><!-- /.form-field -->
        </div><!-- /.form-row -->
        <div class="form-row">
          <div class="form-field">
            <label class="form-label">
              Description
            </label>
            <textarea class="form-input" name="description" row="5" col="3">{{description}}</textarea>
          </div><!-- /.form-field -->
        </div><!-- /.form-row -->
        <div class="form-row">
          <div class="form-field">
            <label class="form-label">
              Category
            </label>
            <p class="alignment-justify">
              <a href="javascript:;" class="btn btn-action">
                Edit category
              </a>
              <span class="f-sml c-pdg-bas-l">Select where in your store's menu that you want your customers to find your product.</span>
            </p>
          </div><!-- /.form-field -->
        </div><!-- /.form-row -->
        <hr class="hr">
        <div class="form-row">
          <div class="form-field input-4">
            <label class="form-label">
              Price ($)
            </label>
            <input type="text" class="form-input" name="price" value="{{price}}">
          </div><!-- /.form-field -->
          <div class="form-field input-4 input-addon">
            <label class="form-label">
              Sales price
            </label>
            <input type="text" class="form-input" name="sale_price" value="{{sale_place}}">
            <a href="javascript:;" class="form-input-addon">
              OFF
            </a>
          </div><!-- /.form-field -->
        </div><!-- /.form-row -->
        <div class="form-row">
          <div class="form-field input-4">
            <label class="form-label">
              Quantity
            </label>
            <input type="text" class="form-input" name="quantity" value="{{quantity}}">
          </div><!-- /.form-field -->
        </div><!-- /.form-row -->
        <p class="c-pdg-bas-v">
          <a href="javascript:;" class="strong c-blk">Add option</a>  - For different sizes, colors, styles, etc.
        </p>
        <hr class="hr">
        <p class="c-pdg-bas-v">
          <a href="javascript:;" class="strong -c-blk">
            Edit Shipping option
          </a>
        </p>
        <hr class="hr">
        <div class="form-row">
          <div class="form-field input-4">
            <label class="form-label">
              Status
            </label>
            <span class="form-select-holder">
              <select class="form-input" name="status">
                {{#each statusList}}
                <option value="{{this}}" {{isSelected this default=../status attribute="selected"}}>{{this}}</option>
                {{/each}}
              </select>
            </span>
          </div><!-- /.form-field -->
        </div><!-- /.form-row -->
        <hr class="hr" style="margin-left:-40px; margin-right:-40px;">
        <p class="ta-r c-pdg-v">
          <a href="javascript:;" class="btn btn-borderless js-cancel-form">
            Cancel 
          </a>
          <a href="javascript:;" class="btn btn-valid js-submit-form">
            Save product
          </a>
        </p>
      </form>
    </div><!-- /.nano-content -->
  </div><!-- /.nano -->
</div><!-- /.view-third -->
<div class="view-third">
  <div class="nano">
    <div class="nano-content">
      <div class="c-pdg-t product-gallery">
        <strong class="strong dp-b" style="margin:50px 0 10px;">Images</strong>
        <a href="javascript:;" class="btn btn-action dp-b ta-c">
          Add image
        </a>
      </div>
      <div class="c-pdg-h c-pdg-bas-t c-pdg-b ta-c">
        <div class="thmb-col">
          <a href="javascript:;" class="thmb product">
            <i class="icon icon-handler"></i>
            <span class="thmb-img" style="background-image:url(/src/fixtures/img/product-01.jpg)"></span>
          </a>
          <a href="javascript:;" class="thmb product">
            <i class="icon icon-handler"></i>
            <span class="thmb-img" style="background-image:url(/src/fixtures/img/product-02.jpg)"></span>
          </a>
        </div><!-- /.thmb-col  -->
      </div>
    </div><!-- /.nano-content -->
  </div><!-- /.nano -->
</div><!-- /.view-third -->
