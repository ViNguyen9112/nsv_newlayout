
<style>
#table-modal span{
    font-weight: normal !important;
}
#report_event {
    width: 100%;
    border: 1px solid #d8d1d1 !important;
    padding:5px;
}
</style>
<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="min-width: 860px;">
        <div class="modal-content">
            <div class="modal-body">
                <table class="table table-borderless">
                    <thead>
                    </thead>
                    <tbody id="table-modal">
                    <tr>
                        <th class='table-th'>StaffID: <span id="StaffID">P0001</span></th>
                        <th class='table-th'>Name: <span id="StaffName">Admin</span></th>
                        <th class='table-th'>Date: <span id="date">2020/11/17</span></th>
                        <th class='table-th'>Time: <span id="time">17:00:00</span></th>
                    </tr>
                    <tr>
                        <th class='table-th'>Customer ID: <span id="CustomerID">A0001</span></th>
                        <th colspan="3" class='table-th'>Customer Name: <span id="CustomerName">Denki Solar Việt Nam</span></th>
                    </tr>
                    <tr>
                        <th class='table-th'>Report</th>
                    </tr>
                    <tr>
                        <th colspan="4"><textarea readonly rows="10" max-rows="10" id="report_event">Using color to add meaning only provides a visual indication, which will not be conveyed to users of assistive technologies – such as screen readers. Ensure that information denoted by the color is either obvious from the content itself (e.g. the visible text), or is included through alternative means, such as additional text hidden with the .sr-only class.</textarea></th>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0)" data-toggle="modal" data-checkIn="" data-checkout="" id="Picture">
                    <i class="material-icons">&#xe420;</i>
                </a>
                <button type="button" class="btn btn-default" id="close-report">Back</button>
            </div>
        </div>
    </div>
</div>
