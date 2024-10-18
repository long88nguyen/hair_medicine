function ChangeToSlug() {
    var title, slug;

    //Lấy text từ thẻ input title
    title = document.getElementById("title").value;

    //Đổi chữ hoa thành chữ thường
    slug = title.toLowerCase();

    //Đổi ký tự có dấu thành không dấu
    slug = slug.replace(/á|à|ả|ạ|ã|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/gi, "a");
    slug = slug.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/gi, "e");
    slug = slug.replace(/i|í|ì|ỉ|ĩ|ị/gi, "i");
    slug = slug.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/gi, "o");
    slug = slug.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/gi, "u");
    slug = slug.replace(/ý|ỳ|ỷ|ỹ|ỵ/gi, "y");
    slug = slug.replace(/đ/gi, "d");
    //Xóa các ký tự đặt biệt
    slug = slug.replace(
        /\`|\~|\!|\@|\#|\||\$|\%|\^|\&|\*|\(|\)|\+|\=|\,|\.|\/|\?|\>|\<|\'|\"|\:|\;|_/gi,
        ""
    );
    //Đổi khoảng trắng thành ký tự gạch ngang
    slug = slug.replace(/ /gi, "-");
    //Đổi nhiều ký tự gạch ngang liên tiếp thành 1 ký tự gạch ngang
    //Phòng trường hợp người nhập vào quá nhiều ký tự trắng
    slug = slug.replace(/\-\-\-\-\-/gi, "-");
    slug = slug.replace(/\-\-\-\-/gi, "-");
    slug = slug.replace(/\-\-\-/gi, "-");
    slug = slug.replace(/\-\-/gi, "-");
    //Xóa các ký tự gạch ngang ở đầu và cuối
    slug = "@" + slug + "@";
    slug = slug.replace(/\@\-|\-\@|\@/gi, "");
    //In slug ra textbox có id “slug”
    document.getElementById("slug").value = slug;
}

var admin = {
    method: 'post',
    extraUrl: '',
    mediaUrl: '/admin/media/index',
    params: {},
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    },

    submit: function () {
        var _data = new FormData();
        $.each(admin.params, function (key, value) {
            _data.append(key, value);
        });

        $.ajax({
            url: settings.baseUrl + admin.extraUrl,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false,
            type: admin.method,
            data: _data,
            headers: admin.headers,
            success: function (response) {
                admin.success(response);
            }
        });
    },

    generateSlug: function (obj, targetID, table) {
        this.params.title = obj.value;
        this.params.table = table;
        this.params.locale = (String($('.locale-select-box').val()) === "undefined" ? '' : $('.locale-select-box').val());
        this.slugObj = $('#' + targetID);
        this.extraUrl = '/admin/generate-slug';
        this.submit();
    },

    execCommand: function () {
        let command = prompt("Command: ");
        if (command != null) {
            this.params.command = command;
        }
        this.extraUrl = '/admin/exec-command';
        this.submit();
    },

    addTag: function () {
        let tagName = $('input[name="tag_name"]').val();
        if (tagName == '') {
            this.toggleAddingTagForm();
            return false;
        }
        this.params.tag_name = tagName;
        this.extraUrl = '/admin/tags/add';
        this.submit();
    },

    success: function (response) {
        if (response.status == true) {
            if (response.task == 'searchMenuItem') {
                admin.menuTab.next().html(response.html);
            }

            if (response.task == 'get-categories') {
                $('select.categories-box').html(response.html);
            }


            if (response.task == 'generate_slug') {
                admin.slugObj.val(response.slug);
            }

            if (response.task == 'delete-media') {
                $('#record-' + response.id).fadeOut();
            }

            if (response.task == 'get-related-posts') {
                $('#relevant-posts').html(response.html);
            }

            if (response.controller = 'TagController' && response.task == 'add') {
                simpleAutocomplete.select(null, response);
                this.toggleAddingTagForm();
            }

            if (response.task == '/upload/crop') {
                parentWindow.document.getElementById('image-preview').src = settings.cdnUrl + response.image;
                parentWindow.document.getElementById(parentWindow.inputID).value = response.image;
                window.close();
            }

            if (response.task == 'clearCacheAndConfig' || response.task == 'symlink') {
                $('.alert div').html(response.msg);
                $('#warning-msg').fadeIn();
                setTimeout(function () {
                    $('#warning-msg').fadeOut();
                }, 3000);
            }
            if (response.task == 'order_number') {
                if (confirm('Đã tồn tại thứ tự này bạn có muốn thay thế không ?')) {
                    var id_affiliate_change = (response.item.id);
                    this.params.id_affiliate_change = id_affiliate_change;
                    this.extraUrl = '/admin/numberOrder/change';
                    this.submit();
                } else {

                }
            }

            if (response.task == 'delete-file-posts') {
                $('#name-file').html('<span class="text-success">' + response.message + '</span>');
                $('#delete-file-posts').hide();
            }
        }
    },

    toggleAddingTagForm: function () {
        $(".adding-tag-form-toggle").toggle();
    },

    getRelatedPosts: function (post_id) {
        this.params.keyword = $.trim($('#keyword-to-find-related-posts').val());
        if (typeof (post_id) !== 'undefined' && post_id > 0) {
            this.params.post_id = post_id;
        }

        if (this.params.keyword != '') {
            this.extraUrl = '/admin/posts/get-related-posts';
            this.submit();
        }
    },
    updateOrderNumber: function (obj) {
        this.params.id = $(obj).data('id');
        this.params.order_number = $(obj).val();
        this.extraUrl = '/admin/jobs/update-order-number';
        this.submit();
    },
    getRelatedJobs: function (job_id) {
        this.params.keyword = $.trim($('#keyword-to-find-related-jobs').val());
        if (typeof (job_id) !== 'undefined' && job_id > 0) {
            this.params.job_id = job_id;
        }

        if (this.params.keyword != '') {
            this.extraUrl = '/admin/jobs/get-related-jobs';
            this.submit();
        }
    },

    selectRelatedJob: function (obj, rid) {
        if ($(obj).is(':checked')) {
            var add_more = true;

            if (admin.params.relatedJobs != '') {
                var ref_arr = admin.params.relatedJobs.split(',');

                for (var i in ref_arr) {
                    if (ref_arr[i] == rid) {
                        add_more = false;
                    }
                }
            }

            if (add_more) {
                admin.params.relatedJobs += (admin.params.relatedJobs != '' ? "," : "") + rid;
            }
        } else {
            if (admin.params.relatedJobs != '') {
                var ref_arr = admin.params.relatedJobs.split(',');

                admin.params.relatedJobs = '';

                for (var i in ref_arr) {
                    if (ref_arr[i] != rid) {
                        admin.params.relatedJobs += (admin.params.relatedJobs != '' ? "," : "") + ref_arr[i];
                    }
                }
            }
        }
    },

    selectRelatedPost: function (obj, rid) {
        if ($(obj).is(':checked')) {
            var add_more = true;

            if (admin.params.relatedPosts != '') {
                var ref_arr = admin.params.relatedPosts.split(',');

                for (var i in ref_arr) {
                    if (ref_arr[i] == rid) {
                        add_more = false;
                    }
                }
            }

            if (add_more) {
                admin.params.relatedPosts += (admin.params.relatedPosts != '' ? "," : "") + rid;
            }
        } else {
            if (admin.params.relatedPosts != '') {
                var ref_arr = admin.params.relatedPosts.split(',');

                admin.params.relatedPosts = '';

                for (var i in ref_arr) {
                    if (ref_arr[i] != rid) {
                        admin.params.relatedPosts += (admin.params.relatedPosts != '' ? "," : "") + ref_arr[i];
                    }
                }
            }
        }
    },

    showLogModal: function (title, des) {
        var logModal = new bootstrap.Modal(document.getElementById('logModal'), {
            keyboard: false
        });

        document.getElementById('modal-content').innerHTML = des;
        logModal.show();
    },

    initWidgetTheme: function () {
        $("#menu-toggle").click(function (e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });

        $(window).on("load resize", function (event) {
            var windowWidth = $(window).width();
            if (windowWidth < 1010) {
                $('body').addClass('small-device');
            } else {
                $('body').removeClass('small-device');
                $("#wrapper").addClass("toggled");
            }

        });
    },

    showCropPopup: function (image, inputID, title) {
        //var imagePreviewSrc = document.getElementById('image-preview').src;
        var imagePreviewSrc = $("#" + inputID).parent().find('img').attr('src');

        if (imagePreviewSrc.indexOf("default.jpg") == -1) {
            image = imagePreviewSrc.replace(settings.cdnUrl, "");
        }

        if (typeof (image) == 'undefined') {
            alert('Anh/Chị cần tải ảnh lên trước khi cắt ảnh!')
            return false;
        } else {
            if (image == '') {
                if (imagePreviewSrc.indexOf("default.jpg") !== -1) {
                    alert('Anh/Chị cần tải ảnh lên trước khi cắt ảnh!')
                    return false;
                }
            }
        }

        image = image.replace('custom/', '');
        image = image.replace('large/', '');
        image = image.replace('medium/', '');
        image = image.replace('thumbnail/', '');
        //document.getElementById('image-preview').src = settings.cdnUrl + image;
        $("#" + inputID).parent().find('img').attr('src', settings.cdnUrl + image);

        var url = '/admin/media/crop';
        var title = 'Media';
        var w = 980;
        var h = 500;

        var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
        var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

        var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        var left = ((width / 2) - (w / 2)) + dualScreenLeft;
        var top = ((height / 2) - (h / 2)) + dualScreenTop;
        var cropWindow = window.open(url, title, 'scrollbars=yes,location=no,toolbar=no,toolbar=no,width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        window.cropImage = image;
        window.inputID = inputID;

        if (window.focus) {
            cropWindow.focus();
        }

        return false;
    },

    menuInit: function () {
        var options = {
            hintCss: { 'border': '1px dashed #13981D' },
            placeholderCss: { 'background-color': 'gray' },
            ignoreClass: 'btn',
            opener: {
                active: true,
                as: 'html',
                close: '<i class="fas fa-minus"></i>',
                open: '<i class="fas fa-plus"></i>',
                openerCss: { 'margin-right': '10px' },
                openerClass: 'btn btn-success btn-xs'
            }
        };
        menuEditor.init('menu', { listOptions: options, labelEdit: 'Edit', labelRemove: 'X' });
    },

    searchItem: function (obj, type) {
        this.menuTab = $(obj);
        this.params.keyword = obj.value;
        this.params.language = $(obj).data('lang');
        this.params.type = type;
        this.extraUrl = '/admin/menu/search';
        this.submit();
    },

    selectFiles: function (path, name, mediaID, type) {
        var opener = window.opener;
        if (opener) {
            if (opener.isMultiple) {
                var html = '';
            } else {
                if (opener.isTinymce) {
                    var caption = prompt("Description for " + (type > 0 ? 'media' : 'image'), (type > 0 ? '' : "Ảnh minh họa"));
                    var insertHTML = '';

                    if (type > 0) {
                        var sourceType = type == 1 ? 'video/mp4' : 'audio/mpeg';
                        var media = '     <' + (type == 1 ? 'video' : 'audio') + ' width="500" height="320" class="mw-100" controls><source src="' + path + '" type="' + sourceType + '"></' + (type == 1 ? 'video' : 'audio') + '>';
                    } else {
                        var media = '     <img class="img-responsive" src="' + path + '" alt="' + caption + '"/>';
                    }

                    if ((caption != null)) {
                        insertHTML += '<div class="text-center">';
                        insertHTML += ' <figure class="d-inline-block text-center mx-auto">';
                        insertHTML += media;
                        insertHTML += '     <figcaption class="d-block text-center bg-light text-primary">' + caption + '</figcaption>';
                        insertHTML += ' </figure>';
                        insertHTML += '</div><p></p>';
                    } else {
                        insertHTML += media;
                    }

                    opener.activeEditor.execCommand('mceInsertRawHTML', false, insertHTML);
                    opener.activeEditor.focus();
                    opener.activeEditor.selection.select(opener.activeEditor.getBody(), true);
                    opener.activeEditor.selection.collapse(false);
                } else {
                    if (type > 0) {
                        alert("Ảnh đại diện không chấp nhận tệp tin: " + name);
                        return false;
                    }
                    var oDom = opener.document;
                    oDom.getElementById(opener.imgID).setAttribute('src', path);
                    oDom.getElementById(opener.inputID).setAttribute('value', path);
                }

                opener.mediaWindow.close();
            }

            opener.close();
        }
    },
    mediaIndex: function (imgID, inputID, isMultiple) {
        var w = 900;
        var h = 550;

        var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
        var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

        var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
        var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

        var systemZoom = width / window.screen.availWidth;
        var left = (width - w) / 2 / systemZoom + dualScreenLeft
        var top = (height - h) / 2 / systemZoom + dualScreenTop

        var popupUrl = admin.mediaUrl;

        if (imgID !== null) {
            window.isTinymce = false;
            popupUrl = admin.mediaUrl + "?type=0";
        }

        window.mediaWindow = window.open(popupUrl, 'Media management', 'scrollbars=yes, width=' + w / systemZoom + ', height=' + h / systemZoom + ', top=' + top + ', left=' + left);

        window.imgID = imgID;
        window.inputID = inputID;
        window.isMultiple = (typeof (isMultiple) !== 'undefined' && isMultiple) ? true : false;
        if (window.focus) {
            window.mediaWindow.focus();
        }
        return false;
    },

    deleteMedia: function (id, name) {
        if (!confirm('Are you sure to delete file : ' + name + '?')) {
            return false;
        }

        this.params.id = id;
        this.extraUrl = '/admin/media/delete-media';
        this.submit();
    },

    deleteFilePosts: function (name) {
        if (!confirm('Are you sure to delete file : ' + name + '?')) {
            return false;
        }
        this.params.file_name = name;
        this.extraUrl = '/admin/posts/delete-file-posts';
        this.submit();
    },

    initTinyMCE: function (selector) {
        tinyMCE.init({
            selector: selector,
            menubar: false,
            relative_urls: true,
            document_base_url: settings.baseUrl,
            theme: 'silver',
            forced_root_block: 'p',
            //width: 900,
            height: 600,
            plugins: [
                'advlist autolink link lists charmap print preview hr anchor pagebreak spellchecker',
                'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
                'save table directionality emoticons template paste autosave image'
            ],
            toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | preview fullpage | forecolor backcolor emoticons | openMediaPopupButton uploadImageButton uploadMediaButton embedYoutube restoredraft code image',
            paste_data_images: true,
            autosave_ask_before_unload: false,
            autosave_restore_when_empty: false,
            entity_encoding: 'raw',
            image_caption: true,
            valid_elements : '*[*]',
            setup: function (editor) {
                editor.ui.registry.addButton('openMediaPopupButton', {
                    text: 'Album',
                    onAction: function (_) {
                        window.cms_tinyMCE = tinyMCE;
                        window.activeEditor = editor;
                        window.isTinymce = true;
                        admin.mediaIndex(null, null, false, true);
                        return false;
                    }
                });

                editor.ui.registry.addButton('uploadImageButton', {
                    text: 'Insert image',
                    onAction: function (_) {
                        uploader.trigger('tinymce-content', true);
                        return false;
                    }
                });

                editor.ui.registry.addButton('uploadMediaButton', {
                    text: 'Insert audio/video',
                    onAction: function (_) {
                        uploader.trigger('tinymce-content', true, null, 'media');
                        return false;
                    }
                });

                editor.ui.registry.addButton('embedYoutube', {
                    text: 'Youtube',
                    onAction: function (_) {
                        var dialogConfig = {
                            title: 'Embed Youtube',
                            body: {
                                type: 'panel',
                                items: [
                                    {
                                        type: 'input',
                                        name: 'url',
                                        label: 'Youtube link: '
                                    },
                                    {
                                        type: 'input',
                                        name: 'description',
                                        label: 'Description: '
                                    }
                                ]
                            },
                            buttons: [
                                {
                                    type: 'cancel',
                                    name: 'closeButton',
                                    text: 'Hủy'
                                },
                                {
                                    type: 'submit',
                                    name: 'submitButton',
                                    text: 'Insert embed code',
                                    primary: true
                                }
                            ],
                            onSubmit: function (api) {
                                var data = api.getData();
                                var url = data.url;
                                var description = data.description;
                                var youtubeVdieoID = admin.getYoutubeVideoID(url);
                                if (youtubeVdieoID !== '') {
                                    iframe = '<iframe class="mw-100" type="text/html" width="100%" height="450" src="https://www.youtube.com/embed/' + youtubeVdieoID + '?autoplay=1" frameborder="0"></iframe>';
                                    var insertHTML = '';
                                    insertHTML += '<div class="text-center">';
                                    insertHTML += ' <figure class="d-inline-block text-center w-100">';
                                    insertHTML += iframe;
                                    if ((description != '')) {
                                        insertHTML += '     <figcaption class="d-block text-center bg-light text-primary">' + description + '</figcaption>';
                                    }
                                    insertHTML += ' </figure>';
                                    insertHTML += '</div><p></p>';
                                    tinymce.activeEditor.execCommand('mceInsertContent', false, insertHTML);
                                }
                                api.close();
                            }
                        };

                        tinymce.activeEditor.windowManager.open(dialogConfig);
                    }
                });
            },
            file_picker_callback: function (callback, value, meta) {
                //nothing
            },

        });
    },

    getYoutubeVideoID: function (url) {
        var pattern = /(?:youtube(?:-nocookie)?\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/g
        var matches = pattern.exec(url);
        if (!Array.isArray(matches)) {
            return false;
        }
        return matches[1];
    },

    sortCategory: function () {
        $('#sortable').sortable({
            start: function (event, ui) {

            },
            stop: function (event, ui) {
                let ids = [];

                $('.sort-item').each(function (k) {
                    ids.push($(this).attr('data-id'));
                });

                admin.sort(ids);
            }
        });

        $('#sortable').disableSelection();
    },

    sort: function (ids) {
        this.params.ids = ids;
        this.extraUrl = '/admin/categories/sort';
        this.submit();
    },

    previewVideo: function (path, previewId) {
        let videoId = admin.getYoutubeVideoID(path);
        let html = '';
        if (videoId) {
            html = '<iframe width="560" height="315" class="mw-100" src="https://www.youtube.com/embed/' + videoId + '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        } else {
            html = '<iframe width="560" height="315" class="mw-100" src="' + path + '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }

        document.getElementById(previewId).innerHTML = html;
    },

};

$(document).ready(function () {
    admin.initWidgetTheme();
});
