<div class="modal fade bd-example-modal-lg" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <form class="form-inline" action="{{url('admin/update-note-admin-multi')}}" method="POST">
          @csrf
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title text-center" id="exampleModalLongTitle"></h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="container">
              <div class="row">
                  <div class="container">
                    <input type="hidden" id="approve_id" name="approve_id" value="">
                    <div class="row">
                        <div class="col-12 text-center" style="margin-top: 20px; margin-bottom: 20px;">
                            <h4 style="    text-transform: uppercase;">Duyệt Hồ Sơ</h4>
                        </div>
                        <table class="table table-hover table-responsive">
                          <tbody class="col">
                            <tr>
                              <td>
                                 <label class="text-boil" for="inputEmail4">Duyệt hồ sơ :</label>
                              </td>
                              <td>
                                  <select class="custom-select my-1 mr-sm-2 form-control" id="inlineFormCustomSelectPref" name="status">
                                    @foreach($logsstatus as $key => $data)
                                      <option value="{{$data->id}}">{{$data->name}}</option>
                                    @endforeach
                                  </select>
                              </td>
                            </tr>
                            <tr>
                              <td>
                                 <label class="text-boil" for="inputEmail4">Duyệt hồ sơ :</label>
                              </td>
                              <td>
                                 <textarea name="admin_note" class="form-control" placeholder="Nhập ghi chú.." id="" cols="30" style="width: 100%; " ></textarea>
                              </td>
                            </tr>
                            
                          </tbody>
                        </table>
                    </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer text-center">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
            <button type="submit" class="btn btn-primary">Duyệt</button>
          </div>
        </div>
        </form>
      </div>
    </div>
