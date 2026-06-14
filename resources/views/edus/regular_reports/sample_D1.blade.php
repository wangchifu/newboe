            <div class="container my-5">
                
                <div class="card mb-4">
                    <div class="card-header bg-light fw-bold">學生上學交通方式調查</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-center mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="min-width: 80px;">項目</th>
                                        <th scope="col">步行</th>
                                        <th scope="col">騎自行車</th>
                                        <th scope="col">騎電動輔助自行車<br><small class="text-muted">(滿14歲)</small></th>
                                        <th scope="col">騎微型電動二輪車<br><small class="text-muted">(滿14歲)</small></th>
                                        <th scope="col">騎機車<br><small class="text-muted">(滿18歲)</small></th>
                                        <th scope="col">搭公車<br><span class="text-danger small">(含幸福巴士)</span></th>
                                        <th scope="col">搭學校專車</th>
                                        <th scope="col">家長接送<br><small class="text-muted">(機車)</small></th>
                                        <th scope="col">家長接送<br><small class="text-muted">(汽車)</small></th>
                                        <th scope="col" style="min-width: 90px;">合計</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row" class="table-light">人數</th>
                                        <td><input type="number" name="go_walk_count" class="form-control form-control-sm text-center"></td>
                                        <td><input type="number" name="go_bike_count" class="form-control form-control-sm text-center"></td>
                                        <td><input type="number" name="go_ebike_count" class="form-control form-control-sm text-center"></td>
                                        <td><input type="number" name="go_escooter_count" class="form-control form-control-sm text-center"></td>
                                        <td><input type="number" name="go_motor_count" class="form-control form-control-sm text-center"></td>
                                        <td><input type="number" name="go_bus_count" class="form-control form-control-sm text-center"></td>
                                        <td><input type="number" name="go_school_bus_count" class="form-control form-control-sm text-center"></td>
                                        <td><input type="number" name="go_parent_motor_count" class="form-control form-control-sm text-center"></td>
                                        <td><input type="number" name="go_parent_car_count" class="form-control form-control-sm text-center"></td>
                                        <td class="table-light fw-bold">
                                            <input type="number" name="go_total_count" class="form-control form-control-sm text-center fw-bold bg-light" readonly placeholder="自動計算">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="table-light">比率</th>
                                        <td><input type="text" name="go_walk_rate" class="form-control form-control-sm text-center" placeholder="%"></td>
                                        <td><input type="text" name="go_bike_rate" class="form-control form-control-sm text-center" placeholder="%"></td>
                                        <td><input type="text" name="go_ebike_rate" class="form-control form-control-sm text-center" placeholder="%"></td>
                                        <td><input type="text" name="go_escooter_rate" class="form-control form-control-sm text-center" placeholder="%"></td>
                                        <td><input type="text" name="go_motor_rate" class="form-control form-control-sm text-center" placeholder="%"></td>
                                        <td><input type="text" name="go_bus_rate" class="form-control form-control-sm text-center" placeholder="%"></td>
                                        <td><input type="text" name="go_school_bus_rate" class="form-control form-control-sm text-center" placeholder="%"></td>
                                        <td><input type="text" name="go_parent_motor_rate" class="form-control form-control-sm text-center" placeholder="%"></td>
                                        <td><input type="text" name="go_parent_car_rate" class="form-control form-control-sm text-center" placeholder="%"></td>
                                        <td class="table-light fw-bold">
                                            <input type="text" name="go_total_rate" class="form-control form-control-sm text-center fw-bold bg-light" readonly placeholder="100%">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-light fw-bold">學生放學交通方式調查</div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle text-center mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col" style="min-width: 80px;">項目</th>
                                        <th scope="col">步行</th>
                                        <th scope="col">騎自行車</th>
                                        <th scope="col">騎電動輔助自行車<br><small class="text-muted">(滿14歲)</small></th>
                                        <th scope="col">騎微型電動二輪車<br><small class="text-muted">(滿14歲)</small></th>
                                        <th scope="col">騎機車<br><small class="text-muted">(滿18歲)</small></th>
                                        <th scope="col">搭公車<br><span class="text-danger small">(含幸福巴士)</span></th>
                                        <th scope="col">搭學校專車</th>
                                        <th scope="col">家長接送<br><small class="text-muted">(機車)</small></th>
                                        <th scope="col">家長接送<br><small class="text-muted">(汽車)</small></th>
                                        <th scope="col">家長接送<br><small class="text-muted">(安親班)</small></th>
                                        <th scope="col" style="min-width: 90px;">合計</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th scope="row" class="table-light">人數</th>
                                        <td><input type="number" name="back_walk_count" class="form-control form-control-sm text-center"></td>
                                        <td><input type="number" name="back_bike_count" class="form-control form-control-sm text-center"></td>
                                        <td><input type="number" name="back_ebike_count" class="form-control form-control-sm text-center"></td>
                                        <td><input type="number" name="back_escooter_count" class="form-control form-control-sm text-center"></td>
                                        <td><input type="number" name="back_motor_count" class="form-control form-control-sm text-center"></td>
                                        <td><input type="number" name="back_bus_count" class="form-control form-control-sm text-center"></td>
                                        <td><input type="number" name="back_school_bus_count" class="form-control form-control-sm text-center"></td>
                                        <td><input type="number" name="back_parent_motor_count" class="form-control form-control-sm text-center"></td>
                                        <td><input type="number" name="back_parent_car_count" class="form-control form-control-sm text-center"></td>
                                        <td><input type="number" name="back_care_count" class="form-control form-control-sm text-center"></td>
                                        <td class="table-light fw-bold">
                                            <input type="number" name="back_total_count" class="form-control form-control-sm text-center fw-bold bg-light" readonly placeholder="自動計算">
                                        </td>
                                    </tr>
                                    <tr>
                                        <th scope="row" class="table-light">比率</th>
                                        <td><input type="text" name="back_walk_rate" class="form-control form-control-sm text-center" placeholder="%"></td>
                                        <td><input type="text" name="back_bike_rate" class="form-control form-control-sm text-center" placeholder="%"></td>
                                        <td><input type="text" name="back_ebike_rate" class="form-control form-control-sm text-center" placeholder="%"></td>
                                        <td><input type="text" name="back_escooter_rate" class="form-control form-control-sm text-center" placeholder="%"></td>
                                        <td><input type="text" name="back_motor_rate" class="form-control form-control-sm text-center" placeholder="%"></td>
                                        <td><input type="text" name="back_bus_rate" class="form-control form-control-sm text-center" placeholder="%"></td>
                                        <td><input type="text" name="back_school_bus_rate" class="form-control form-control-sm text-center" placeholder="%"></td>
                                        <td><input type="text" name="back_parent_motor_rate" class="form-control form-control-sm text-center" placeholder="%"></td>
                                        <td><input type="text" name="back_parent_car_rate" class="form-control form-control-sm text-center" placeholder="%"></td>
                                        <td><input type="text" name="back_care_rate" class="form-control form-control-sm text-center" placeholder="%"></td>
                                        <td class="table-light fw-bold">
                                            <input type="text" name="back_total_rate" class="form-control form-control-sm text-center fw-bold bg-light" readonly placeholder="100%">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-light fw-bold">
                                交通安全教育輔導與管理 —— 是否提供學生車輛停放空間 <span class="text-danger small">(1車1格)</span>
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-bordered align-middle text-center mb-0" style="height: 100%;">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">自行車格數</th>
                                            <th scope="col">電動輔助自行車格數</th>
                                            <th scope="col">微型電動二輪車格數</th>
                                            <th scope="col">機車格數</th>
                                            <th scope="col">合計</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="number" name="park_bike" class="form-control text-center"></td>
                                            <td><input type="number" name="park_ebike" class="form-control text-center"></td>
                                            <td><input type="number" name="park_escooter" class="form-control text-center"></td>
                                            <td><input type="number" name="park_motor" class="form-control text-center"></td>
                                            <td class="table-light"><input type="number" name="park_total" class="form-control text-center fw-bold bg-light" readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-light fw-bold">
                                是否針對騎車學生進行考照輔導
                            </div>
                            <div class="card-body p-0">
                                <table class="table table-bordered align-middle text-center mb-0" style="height: 100%;">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">自行車考照</th>
                                            <th scope="col">電動輔助自行車考照</th>
                                            <th scope="col">微型電動二輪車考照</th>
                                            <th scope="col">合計</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="number" name="guide_bike" class="form-control text-center"></td>
                                            <td><input type="number" name="guide_ebike" class="form-control text-center"></td>
                                            <td><input type="number" name="guide_escooter" class="form-control text-center"></td>
                                            <td class="table-light"><input type="number" name="guide_total" class="form-control text-center fw-bold bg-light" readonly></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>   