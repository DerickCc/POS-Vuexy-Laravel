<div class="modal fade" id="paymentModal" aria-hidden="true" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Pembayaran</h5>
        <button class="btn-close" data-bs-dismiss="modal" type="button" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4 pb-4 border-bottom">
          <div class="col-md-6">
            <label class="form-label" for="soCodeM">No. Invoice</label>
            <input class="form-control" id="soCodeM" name="soCode" readonly>
          </div>
          <div class="col-md-6">
            <label class="form-label" for="grandTotalM">Grand Total</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input class="form-control" id="grandTotalM" name="grand_total" readonly />
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label" for="paidAmountM">Jumlah Yang Telah Dibayar</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input class="form-control" id="paidAmountM" name="paid_amount" readonly />
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label" for="shouldBePaidM">Jumlah Yang Harus Dilunasi</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input class="form-control" id="shouldBePaidM" readonly />
            </div>
          </div>
        </div>

        <div class="row g-4 pt-3">
          <div class="col-md-6">
            <label class="form-label required" for="paymentAmountM">Jumlah Pembayaran</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input class="form-control" id="paymentAmountM" name="payment_amount" value="0" />
            </div>
          </div>
          <div class="col-md-6">
            <label class="form-label" for="paymentLeftM">Sisa Yang Harus Dilunasi</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input class="form-control" id="paymentLeftM" value="0" readonly />
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-label-secondary" data-bs-dismiss="modal" type="button">Batal</button>
        <button class="btn btn-success" id="submitPaymentModalBtn" type="button">Simpan</button>
      </div>
    </div>
  </div>
</div>
