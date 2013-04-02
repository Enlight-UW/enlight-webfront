<h2>Welcome To Our World!</h2>
<p>This is the status page, where all relevant live statistics
    of the fountain and related software will be represented. These
    statistics are refreshed in real-time as the server finds out about
    changes, so what you\'re seeing here is a true reflection of the
    state of things, as far as the Webfront is concerned. Not to say that
    things couldn\'t seem fine here yet still be broken, though.</p>
<div class="well">
    <dl class="dl-horizontal">


        <dt>Server State</dt>
        <dd>
            <span class="label label-inverse" data-bind="text: fountainState"></span>
        </dd>

        <br />

        <dt>Valve States</dt>
        <dd>
            <div style="position: relative; height: 224px; width: 224px; border: 1px solid #CBB;">

                <!-- Fountain status display (pay no attention to the hacky bitmasking behind the curtain) -->
                <span class="label" style="position: absolute; left: 65px; top: 14px;" data-bind="css: {'label-info': valveState() & 1}">V1</span>
                <span class="label" style="position: absolute; left: 46px; top: 31px;" data-bind="css: {'label-info': valveState() & 2}">V2</span>
                <span class="label" style="position: absolute; left: 33px; top: 50px;" data-bind="css: {'label-info': valveState() & 4}">V3</span>
                <span class="label" style="position: absolute; left: 23px; top: 70px;" data-bind="css: {'label-info': valveState() & 8}">V4</span>
                <span class="label" style="position: absolute; left: 17px; top: 90px;" data-bind="css: {'label-info': valveState() & 16}">V5</span>
                <span class="label" style="position: absolute; left: 17px; top: 110px;" data-bind="css: {'label-info': valveState() & 32}">V6</span>
                <span class="label" style="position: absolute; left: 23px; top: 130px;" data-bind="css: {'label-info': valveState() & 64}">V7</span>
                <span class="label" style="position: absolute; left: 33px; top: 150px;" data-bind="css: {'label-info': valveState() & 128}">V8</span>
                <span class="label" style="position: absolute; left: 46px; top: 169px;" data-bind="css: {'label-info': valveState() & 256}">V9</span>
                <span class="label" style="position: absolute; left: 63px; top: 186px;" data-bind="css: {'label-info': valveState() & 512}">V10</span>

                <span class="label" style="position: absolute; left: 85px; top: 150px;" data-bind="css: {\'label-info': valveState() & 1024}">VC</span>
                <span class="label" style="position: absolute; left: 80px; top: 168px;" data-bind="css: {\'label-info': valveState() & 2048}">VR</span>

                <span class="label" style="position: absolute; left: 120px; top: 35px;" data-bind="css: {'label-info': valveState() & 4096}">H1</span>
                <span class="label" style="position: absolute; left: 145px; top: 40px;" data-bind="css: {'label-info': valveState() & 8192}">H2</span>
                <span class="label" style="position: absolute; left: 168px; top: 50px;" data-bind="css: {'label-info': valveState() & 16384}">H3</span>
                <span class="label" style="position: absolute; left: 183px; top: 67px;" data-bind="css: {'label-info': valveState() & 32768}">H4</span>
                <span class="label" style="position: absolute; left: 190px; top: 85px;" data-bind="css: {'label-info': valveState() & 65536}">H5</span>
                <span class="label" style="position: absolute; left: 185px; top: 103px;" data-bind="css: {'label-info': valveState() & 131072}">H6</span>
                <span class="label" style="position: absolute; left: 178px; top: 121px;" data-bind="css: {'label-info': valveState() & 262144}">H7</span>
                <span class="label" style="position: absolute; left: 168px; top: 138px;" data-bind="css: {'label-info': valveState() & 524288}">H8</span>
                <span class="label" style="position: absolute; left: 154px; top: 155px;" data-bind="css: {'label-info': valveState() & 1048576}">H9</span>
                <span class="label" style="position: absolute; left: 137px; top: 173px;" data-bind="css: {'label-info': valveState() & 2097152}">H10</span>

                <span class="label" style="position: absolute; left: 130px; top: 138px;" data-bind="css: {'label-info': valveState() & 4194304}">HC</span>
                <span class="label" style="position: absolute; left: 130px; top: 155px;" data-bind="css: {'label-info': valveState() & 8388608}">HR</span>

            </div>
            <span class="label">Idle</span> <span class="label label-info">Active</span> <span class="label label-warning">Disabled</span>
        </dd>


    </dl>
</div>


<!-- LMOC -->
<h3>Manual Override <span class="muted">(LMOC 2.0)</span></h3>
<div class="well">
    <p>
        This is a high-priority manual control for the valves.
        Enabling this will immediately invalidate outstanding API
        authorizations and the server will not grant new API access
        until manual intervention is disabled.
    </p>
    <p style="text-align:center;">
        <a href="#" onclick="return false;" class="btn btn-warning">
            Enable Override
        </a>
    </p>

    <!-- TODO: Insert buttons dependant on override state here -->
</div>


<!-- Restricted valves -->
<h3>Valve Restrictions</h3>
<div class="well">
    <p>
        Sometimes valves misbehave and need to be disabeld. If a
        valve is disabled here (indicated on the above status
        diagram), it will not be able to be actuated by the server
        in any manner (not even manual override). Click a valve name
        to toggle it between enabled and disabled.
    </p>
    <table class="table">
        <tr>
            <th>Vertical</th>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    V1
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    V2
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    V3
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    V4
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    V5
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    V6
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    V7
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    V8
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    V9
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    V10
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    VC
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    VR
                </a>
            </td>
        </tr>


        <tr>
            <th>Horizontal</th>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    H1
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    H2
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    H3
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    H4
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    H5
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    H6
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    H7
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    H8
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    H9
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    H10
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    HC
                </a>
            </td>
            <td>
                <a href="#" onclick="return false;" class="btn">
                    HR
                </a>
            </td>
        </tr>
    </table>
</div>