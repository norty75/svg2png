(function(obj) {

	var requestFileSystem = obj.webkitRequestFileSystem || obj.mozRequestFileSystem || obj.requestFileSystem;

	function onerror(message) {
		alert(message);
	}

	function createTempFile(callback) {
		var tmpFilename = "tmp.zip";
		requestFileSystem(TEMPORARY, 4 * 1024 * 1024 * 1024, function(filesystem) {
			function create() {
				filesystem.root.getFile(tmpFilename, {
					create : true
				}, function(zipFile) {
					callback(zipFile);
				});
			}

			filesystem.root.getFile(tmpFilename, null, function(entry) {
				entry.remove(create, create);
			}, create);
		});
	}

	var model = (function() {
		var zipFileEntry, zipWriter, writer, creationMethod, URL = obj.webkitURL || obj.mozURL || obj.URL;

		return {
			setCreationMethod : function(method) {
				creationMethod = method;
			},
			addFiles : function addFiles(files, oninit, onadd, onprogress, onend) {
				var addIndex = 0;

				function nextFile() {
					var file = files[addIndex];
					onadd(file);
					//zipWriter.add(file.name, new zip.BlobReader(file), function() {
					zipWriter.add(file.name, new zip.Data64URIReader(file.data), function() {
						addIndex++;
						if (addIndex < files.length)
							nextFile();
						else
							onend();
					}, onprogress);
				}

				function createZipWriter() {
					zip.createWriter(writer, function(writer) {
						zipWriter = writer;
						oninit();
						nextFile();
					}, onerror, true);
				}

				if (zipWriter)
					nextFile();
				else if (creationMethod == "Blob") {
					writer = new zip.BlobWriter();
					createZipWriter();
				} else {
					createTempFile(function(fileEntry) {
						zipFileEntry = fileEntry;
						writer = new zip.FileWriter(zipFileEntry);
						createZipWriter();
					});
				}
			},
			getBlobURL : function(callback) {
				zipWriter.close(function(blob) {
					var blobURL = creationMethod == "Blob" ? URL.createObjectURL(blob) : zipFileEntry.toURL();
					callback(blobURL);
					zipWriter = null;
				});
			},
			getBlob : function(callback) {
				zipWriter.close(callback);
			}
		};
	})();

	(function() {
		var zipProgress = document.createElement("progress");
		var fileList = document.getElementById("file-list");
		var filenameInput = document.getElementById("filename-input");
		
		model.setCreationMethod("Blob");

	    var src_data = new Array();
		var rawData = new Object();
        rawData.name="first";
        rawData.data="data:image/png;base64,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";
        src_data[0] = rawData;

        /*rawData=new Object();
        rawData.name="zzz";
        rawData.data="Doe";
        src_data[1] = rawData;*/

			model.addFiles(src_data, function() {
			}, function(file) {
				var li = document.createElement("li");
				zipProgress.value = 0;
				zipProgress.max = 0;
				li.textContent = file.name;
				li.appendChild(zipProgress);
				fileList.appendChild(li);
			}, function(current, total) {
				zipProgress.value = current;
				zipProgress.max = total;
			}, function() {
				if (zipProgress.parentNode)
					zipProgress.parentNode.removeChild(zipProgress);
			});		    
		    
			var target = event.target, entry;
			if (!downloadButton.download) {
				if (typeof navigator.msSaveBlob == "function") {
					model.getBlob(function(blob) {
						navigator.msSaveBlob(blob, filenameInput.value);
					});
				} else {
					model.getBlobURL(function(blobURL) {
						var clickEvent;
						clickEvent = document.createEvent("MouseEvent");
						clickEvent.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
						downloadButton.href = blobURL;
						downloadButton.download = filenameInput.value;
						downloadButton.dispatchEvent(clickEvent);
						fileList.innerHTML = "";
					});
					event.preventDefault();
					return false;
				}
			}
		

	})();

})(this);
