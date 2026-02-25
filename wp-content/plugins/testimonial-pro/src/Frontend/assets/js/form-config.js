jQuery(function ($) {
    'use strict';
    /* ==== Chosen ====*/
    $(".chosen-select").chosen({ width: "100%" });
    /* ==== Social Profile Check ====*/
    $(".tpro-social-profile-check").on('change', function () {
        var social_profile_check = $("input[name=tpro_social_profile_check]").attr('checked') ? '1' : '0';
        if (social_profile_check == '1') {
            $('.tpro-social-profile-links').show();
        } else {
            $('.tpro-social-profile-links').hide();
        }
    });
    (function () {
        $("#testimonial_form").validate();
        $("#testimonial_form").on('submit', function () {
            if ($(this).valid()) {
				$(this).find('#submit').css({ "pointer-event": "none", "opacity": 0.75 });
            }
        })

    }());
    jQuery.fn.extend({
        createProfile: function (options = {}) {
            var hasOption = function (optionKey) {
                return options.hasOwnProperty(optionKey);
            };

            var option = function (optionKey) {
                return options[optionKey];
            };

            var generateId = function (string) {
                return string
                    .replace(/\[/g, '_')
                    .replace(/\]/g, '')
                    .toLowerCase();
            };

            var addItem = function (items, key, fresh = true) {
                var itemContent = items;
                var group = itemContent.data("group");
                var item = itemContent;
                var input = item.find('input,select');

                input.each(function (index, el) {
                    var attrName = $(el).data('name');
                    var skipName = $(el).data('skip-name');
                    if (skipName != true) {
                        $(el).attr("name", group + "[" + key + "][" + attrName + "]");
                    } else {
                        if (attrName != 'undefined') {
                            $(el).attr("name", attrName);
                        }
                    }
                    if (fresh == true) {
                        $(el).attr('value', '');
                    }

                    $(el).attr('id', generateId($(el).attr('name')));
                    $(el).parent().find('label').attr('for', generateId($(el).attr('name')));
                })

                var itemClone = items;

                /* Handling remove btn */
                var removeButton = itemClone.find('.tpro-social-profile-remove');
                if (removeButton) {
                    removeButton.attr('onclick', 'jQuery(this).parents(\'.tpro-social-profile-item\').remove()');
                }
                var newItem = $("<div class='tpro-social-profile-item'>" + itemClone.html() + "<div/>");
                newItem.attr('data-index', key)

                newItem.appendTo(repeater);
            };

            /* Find elements */
            var repeater = this;
            var items = repeater.find(".tpro-social-profile-item");
            var key = 0;
            var addButton = $('.tpro-social-profile-wrapper').find('.tpro-add-new-profile-btn');

            items.each(function (index, item) {
                items.remove();
                if (hasOption('showFirstItemToDefault') && option('showFirstItemToDefault') == true) {
                    addItem($(item), key);
                    key++;
                } else {
                    if (items.length > 1) {
                        addItem($(item), key);
                        key++;
                    }
                }
            });
            /* Handle click and add items */
            addButton.on("click", function () {
                addItem($(items[0]), key);
                key++;
            });
        }
    });

    $("#tpro-social-profiles").createProfile({
        showFirstItemToDefault: true,
    });

    let tpro_preview = document.getElementById("tpro_preview");
    let tpro_recording = document.getElementById("tpro_recording");
    let tpro_timer = document.getElementById("tpro_timer-text");
    // var pauseResBtn = document.querySelector('button#pauseRes');
    let tpro_startButton = document.getElementById("tpro_startButton");
    let tpro_stopButton = document.getElementById("tpro_stopButton");
    let tpro_addButton = document.getElementById("tpro_addButton");

    let tpro_recording_constraints = { audio: { noiseSuppression: false }, video: { width: { min: 480, ideal: 800, max: 1280 }, height: { min: 320, ideal: 470, max: 720 }, framerate: 30 } };
    // let tpro_dataElement = document.querySelector('#log');

    // Get the modal.
    var modal = document.getElementById("tpro_video_modal");
    // Get the button that opens the modal.
    var tpro_modal_btn = document.getElementById("tpro_modal_btn");
    // Get the <span> element that closes the modal.
    var tpro_modal_close = document.getElementsByClassName("tpro_modal_close")[0];
    var tpro_no_camera = document.getElementsByClassName("tpro_no_camera")[0];
    var tpro_background = document.getElementsByClassName("tpro_background")[0];

    if (tpro_modal_btn) {

        // When the user clicks on <span> (x), close the modal.
        tpro_background.onclick = function () {
            tpro_modal_close.click();
        }
        tpro_modal_close.onclick = function () {
            modal.style.display = "none";
            tpro_recording.style.display = 'none';
            tpro_addButton.style.display = 'none';
        }
        // When the user clicks anywhere outside of the modal, close it.
        window.onclick = function (event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
        let max_record_time = tpro_timer.getAttribute('data-maxtime');
        let recordingTimeMS = 1000 * 60 * max_record_time;

        var videoTimerInterval = null;
        function startTimer(duration, tpro_timer) {
            var timer = duration, minutes, seconds;
            videoTimerInterval = setInterval(function () {
                minutes = parseInt(timer / 60, 10);
                seconds = parseInt(timer % 60, 10);
                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;
                tpro_timer.innerHTML = minutes + `:` + seconds;
                tpro_timer.style.display = 'inline-block';
                if (--timer < 0) {
                    timer = duration;
                    clearInterval(videoTimerInterval);
                    tpro_stopButton.click();
                }
            }, 1000);
        }

        tpro_preview.controls = false;
        tpro_recording.controls = false;

        var mediaRecorder;
        var chunks = [];
        var localStream = null;
        var soundMeter = null;
        var containerType = "video/webm"; //defaults to webm but we switch to mp4 on Safari 14.0.2+

        // When the user clicks on the button, open the modal.
        tpro_modal_btn.onclick = function (e) {
            e.preventDefault();
            modal.style.display = "block";
            tpro_preview.style.display = 'block';
            tpro_startButton.style.display = 'block';

            if (!navigator.mediaDevices.getUserMedia) {
                alert('navigator.mediaDevices.getUserMedia not supported on your browser, use the latest version of Firefox or Chrome');
                tpro_no_camera.style.display = 'flex';
                $('.tpro_record_video_buttons').css({ 'display': 'none' });
            } else {
                if (window.MediaRecorder == undefined) {
                    alert('MediaRecorder not supported on your browser, use the latest version of Firefox or Chrome');
                    tpro_no_camera.style.display = 'flex';
                    $('.tpro_record_video_buttons').css({ 'display': 'none' });
                } else {
                    navigator.mediaDevices.getUserMedia(tpro_recording_constraints)
                        .then(function (stream) {
                            localStream = stream;
                            localStream.getTracks().forEach(function (track) {
                                if (track.kind == "audio") {
                                    track.onended = function (event) {
                                        log("audio track.onended Audio track.readyState=" + track.readyState + ", track.muted=" + track.muted);
                                    }
                                }
                                if (track.kind == "video") {
                                    track.onended = function (event) {
                                        log("video track.onended Audio track.readyState=" + track.readyState + ", track.muted=" + track.muted);
                                    }
                                }
                            });

                            tpro_preview.srcObject = localStream;
                            tpro_preview.play();

                            try {
                                window.AudioContext = window.AudioContext || window.webkitAudioContext;
                                window.audioContext = new AudioContext();
                            } catch (e) {
                                log('Web Audio API not supported.');
                            }


                        }).catch(function (err) {
                            // Handle the error.
                            tpro_no_camera.style.display = 'flex';
                            $('.tpro_record_video_buttons').css({ 'display': 'none' });
                            log('navigator.getUserMedia error: ' + err);
                        });
                }
            }
        }

        function onBtnRecordClicked() {
            if (localStream == null) {
                alert('Could not get local stream from mic/camera');
            } else {
                tpro_startButton.disabled = true;
                // pauseResBtn.disabled = false;
                tpro_stopButton.disabled = false;

                chunks = [];

                /* use the stream */
                log('Start recording...');
                if (typeof MediaRecorder.isTypeSupported == 'function') {
                    /*
                        MediaRecorder.isTypeSupported is a function announced in https://developers.google.com/web/updates/2016/01/mediarecorder and later introduced in the MediaRecorder API spec http://www.w3.org/TR/mediastream-recording/
                    */
                    if (MediaRecorder.isTypeSupported('video/webm')) {
                        var options = { mimeType: 'video/webm' };
                    } else if (MediaRecorder.isTypeSupported('video/mp4')) {
                        // Safari 14.0.2 has an EXPERIMENTAL version of MediaRecorder enabled by default
                        containerType = "video/mp4";
                        var options = { mimeType: 'video/mp4', videoBitsPerSecond: 2500000 };
                    }

                    log('Using ' + JSON.stringify(options));
                    mediaRecorder = new MediaRecorder(localStream, options);

                } else {
                    log('isTypeSupported is not supported, using default codecs for browser');
                    mediaRecorder = new MediaRecorder(localStream);
                }

                mediaRecorder.ondataavailable = function (e) {
                    // log('mediaRecorder.ondataavailable, e.data.size='+e.data.size);
                    if (e.data && e.data.size > 0) {
                        chunks.push(e.data);
                    }
                };

                mediaRecorder.onerror = function (e) {
                    log('mediaRecorder.onerror: ' + e);
                };

                mediaRecorder.onstart = function () {

                    var lengthInS = recordingTimeMS / 1000;
                    startTimer(lengthInS, tpro_timer);

                    log('mediaRecorder.onstart, mediaRecorder.state = ' + mediaRecorder.state);

                    localStream.getTracks().forEach(function (track) {
                        if (track.kind == "audio") {
                            log("onstart - Audio track.readyState=" + track.readyState + ", track.muted=" + track.muted);
                        }
                        if (track.kind == "video") {
                            log("onstart - Video track.readyState=" + track.readyState + ", track.muted=" + track.muted);
                        }
                    });

                };

                mediaRecorder.onstop = function () {
                    log('mediaRecorder.onstop, mediaRecorder.state = ' + mediaRecorder.state);

                    //var recording = new Blob(chunks, {type: containerType});
                    var recording = new Blob(chunks, { type: mediaRecorder.mimeType });
                    tpro_addButton.href = URL.createObjectURL(recording);
                    // Even if they do, they may only support MediaStream.
                    tpro_recording.src = URL.createObjectURL(recording);
                    tpro_recording.controls = true;
                    // tpro_recording.play();

                    var rand = Math.floor((Math.random() * 10000000));
                    switch (containerType) {
                        case "video/mp4":
                            var name = "video-testimonial-" + rand + ".mp4";
                            break;
                        default:
                            var name = "video-testimonial-" + rand + ".webm";
                    }

                    tpro_addButton.addEventListener("click", (e) => {
                        e.preventDefault();
                        tpro_recording.style.display = 'none';
                        tpro_addButton.style.display = 'none';
                        document.getElementById('tpro_startButton_text').innerText = 'Start Recording';
                        let file = new File([recording], name, {
                            type: recording.type,
                        });
                        let container = new DataTransfer();
                        container.items.add(file);
                        document.querySelector('#tpro_client_video_upload').files = container.files;

                        document.querySelector('.sp-testimonial-video-wrapper video').src = tpro_addButton.href;
                        document.querySelector('.sp-testimonial-video-wrapper').style.display = 'block';

                        tpro_modal_close.click();
                    });

                    // tpro_addButton.innerHTML = 'Download '+name;
                    tpro_addButton.setAttribute("download", name);
                    tpro_addButton.setAttribute("name", name);
                };

                mediaRecorder.onpause = function () {
                    log('mediaRecorder.onpause, mediaRecorder.state = ' + mediaRecorder.state);
                }

                mediaRecorder.onresume = function () {
                    log('mediaRecorder.onresume, mediaRecorder.state = ' + mediaRecorder.state);
                }

                mediaRecorder.onwarning = function (e) {
                    log('mediaRecorder.onwarning: ' + e);
                };

                // pauseResBtn.textContent = "Pause";

                mediaRecorder.start(200);

                localStream.getTracks().forEach(function (track) {
                    log(track.kind + ":" + JSON.stringify(track.getSettings()));
                    log(track.getSettings());
                })
            }
        }

        tpro_startButton.addEventListener("click", () => {
            tpro_no_camera.style.display = 'none';
            tpro_preview.style.display = 'block';
            tpro_recording.style.display = 'none';

            tpro_startButton.style.display = 'none';
            tpro_stopButton.style.display = 'inline-block';

            onBtnRecordClicked();
        });

        tpro_stopButton.addEventListener("click", () => {
            tpro_addButton.style.display = 'inline-block';
            tpro_preview.style.display = 'none';
            tpro_recording.style.display = 'block';
            tpro_timer.style.display = 'none';
            tpro_stopButton.style.display = 'none';
            tpro_startButton.style.display = 'block';
            document.getElementById('tpro_startButton_text').innerText = 'Record again';
            // stop(tpro_preview.srcObject);
            clearInterval(videoTimerInterval);
            tpro_timer.innerText = '';
            onBtnStopClicked();
        }, false);

        navigator.mediaDevices.ondevicechange = function (event) {
            log("mediaDevices.ondevicechange");
        }

        function onBtnStopClicked() {
            mediaRecorder.stop();
            tpro_startButton.disabled = false;
            // pauseResBtn.disabled = true;
            tpro_stopButton.disabled = true;
        }

        // Function to debug error.
        function log(message) {
            // tpro_dataElement.innerHTML = tpro_dataElement.innerHTML+'<br>'+message ;
            // console.log(message)
        }
    }
});
