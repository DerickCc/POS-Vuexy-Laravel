<div class="modal fade" id="productModal" aria-hidden="true" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <form id="productModalForm">
        <div class="modal-header">
          <h5 class="modal-title">Data Barang</h5>
          <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-4">
            <div class="col-12">
              <label class="form-label required" for="selectProduct">Nama Barang</label>
              <select class="select2 form-select" id="selectProduct" nama="id">
              </select>
            </div>
            <div class="col-6">
              <label class="form-label required" for="prdPurchasePrice">Harga Beli</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input
                  class="form-control"
                  id="prdPurchasePrice"
                  name="purchase_price"
                  type="text"
                  placeholder="Harga Beli"
                />
              </div>
            </div>
            <div class="col-6">
              <label class="form-label required" for="prdSellingPrice">Harga Jual</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input
                  class="form-control"
                  id="prdSellingPrice"
                  name="selling_price"
                  type="text"
                  placeholder="Harga Jual"
                />
              </div>
            </div>
            <div class="col-6">
              <label class="form-label required" for="prdQuantity">Qty</label>
              <input
                class="form-control"
                id="prdQuantity"
                type="number"
                value="1"
                placeholder="Qty Barang"
              >
            </div>
            <div class="col-6">
              <label class="form-label required" for="prdUom">Satuan Barang</label>
              <input class="form-control" id="prdUom" type="text" placeholder="Harga Jual">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-label-secondary" data-bs-dismiss="modal" type="button">Batal</button>
          <button class="btn btn-success" id="submitModalBtn" type="submit">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
