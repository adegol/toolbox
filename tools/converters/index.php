<?php include 'header.php'; ?>
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="tabbable tabs-left">
                <ul class="nav nav-tabs">
                    <li><a href="#hash" data-toggle="tab">String to hash</a></li>
                    <li><a href="#bas64" data-toggle="tab">Base64 &lt;-&gt; String</a></li>
                    <li><a href="#hex" data-toggle="tab">Hex &lt;-&gt; String</a></li>
                    <li class="active"><a href="#binary" data-toggle="tab">Binary &lt;-&gt; String</a></li>
                </ul>

                <div class="tab-content">
                    <div id="md5" class="tab-pane">
                        <fieldset>
                            <script>

                            </script>
                            <legend>String to hash</legend>
                            <form method="post" class="well form-inline">
                                <input type="hidden" name="type" value="hash">
                                <input type="text" class="input-xlarge span7" name="string" placeholder="Enter string..." autofocus="true">
                                <input type="submit" class="btn btn-success" value="Submit"><br >
                                <label class="radio">
                                    <input type="radio" name="algo" value="md5" checked>MD5
                                </label>
                                <label class="radio">
                                    <input type="radio" name="algo" value="sha1">SHA1
                                </label>
                            </form>
                            <?php
                                if (isset($_POST['string']) && ($_POST['type'] == 'hash')) {
                                    switch ($_POST['algo']) {
                                        case 'md5':
                                            $output = md5($_POST['string']);
                                            break;
                                        case 'sha1':
                                            $output = sha1($_POST['string']);
                                            break;
                                        default:
                                            $output = '-';
                                            break;
                                    }
                                    echo "<pre>Result: {$output}</pre>";
                                }
                            ?>
                        </fieldset>
                    </div>
                    <div id="base64" class="tab-pane">
                        <fieldset>
                            <legend>Base64 &lt;-&gt; String</legend>
                            <form method="post" class="well form-inline">
                                <input type="hidden" name="type" value="base64">
                                <input type="text" class="input-xlarge span7" name="string" placeholder="Enter string..." autofocus="true">
                                <input type="submit" class="btn btn-success" value="Submit"><br >
                                <label class="radio">
                                    <input type="radio" name="action" value="encode" checked>Encode
                                </label>
                                <label class="radio">
                                    <input type="radio" name="action" value="decode">Decode
                                </label>
                            </form>
                            <?php
                                if (isset($_POST['string']) && ($_POST['type'] == 'base64')) {
                                    switch ($_POST['action']) {
                                        case 'encode':
                                            $output = base64_encode($_POST['string']);
                                            break;
                                        case 'decode':
                                            $output = base64_decode($_POST['string']);
                                            break;
                                        default:
                                            $output = '-';
                                            break;
                                    }
                                    echo "<pre>Result: {$output}</pre>";
                                }
                            ?>
                        </fieldset>
                    </div>
                    <div id="hex" class="tab-pane">
                        <fieldset>
                            <legend>Hex &lt;-&gt; String</legend>
                            <form method="post" class="well form-inline">
                                <input type="hidden" name="type" value="hex">
                                <input type="text" class="input-xlarge span7" name="string" placeholder="Enter string..." autofocus="true">
                                <input type="submit" class="btn btn-success" value="Submit"><br >
                                Action: 
                                <label class="radio">
                                    <input type="radio" name="action" value="encode" checked>Encode
                                </label>
                                <label class="radio">
                                    <input type="radio" name="action" value="decode">Decode
                                </label>
                            </form>
                            <?php
                                if (isset($_POST['string']) && ($_POST['type'] == 'hex')) {
                                    $chars = str_split($_POST['string'], ($_POST['action'] == 'encode') ? 1 : 2);
                                    $output = '';
                                    switch ($_POST['action']) {
                                        case 'encode':
                                            foreach ($chars as $char) {
                                                $output .= dechex(ord($char));
                                            }
                                            break;
                                        case 'decode':
                                            foreach ($chars as $char) {
                                                $output .= chr(hexdec($char));
                                            }
                                            break;
                                        default:
                                            $output = '-';
                                            break;
                                    }
                                    echo "<pre>Result: {$output}</pre>";
                                }
                            ?>
                        </fieldset>
                    </div>
                    <div id="binary" class="tab-pane active">
                        <fieldset>
                            <legend>Hex &lt;-&gt; String</legend>
                            <form method="post" class="well form-inline">
                                <input type="hidden" name="type" value="binary">
                                <input type="text" class="input-xlarge span7" name="string" placeholder="Enter string..." autofocus="true">
                                <input type="submit" class="btn btn-success" value="Submit"><br >
                                Action: 
                                <label class="radio">
                                    <input type="radio" name="action" value="encode" checked>Encode
                                </label>
                                <label class="radio">
                                    <input type="radio" name="action" value="decode">Decode
                                </label>
                            </form>
                            <?php
                                if (isset($_POST['string']) && ($_POST['type'] == 'binary')) {
                                    $chars = str_split($_POST['string'], ($_POST['action'] == 'encode') ? 1 : 8);
                                    $output = '';
                                    switch ($_POST['action']) {
                                        case 'encode':
                                            foreach ($chars as $char) {
                                                $binary = decbin(ord($char));
                                                $output .= str_pad($binary, 8, 0, STR_PAD_LEFT);
                                            }
                                            break;
                                        case 'decode':
                                            foreach ($chars as $char) {
                                                $output .= chr(bindec($char));
                                            }
                                            break;
                                        default:
                                            $output = '-';
                                            break;
                                    }
                                    echo "<pre>Result: {$output}</pre>";
                                }
                            ?>
                        </fieldset>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include 'footer.php'; ?>