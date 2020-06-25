/* *********************************************************************
 * Copyright (C) 2007-2015 VMware, Inc. All Rights Reserved. -- VMware Confidential
 * *********************************************************************/

/*
 * This demonstrates how to set up and use guest operations.
 * This assumes that the guest OS is Linux.
 *
 * It executes 'ls' on the guest OS, redirecting it to a file.
 * It then copies that output file to the host.
 * Finally it cleans up on the guest.
 */

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include "vix.h"

/*
 * guest OS username/password
 */
#define  GUEST_USERNAME "lukas"
#define  GUEST_PASSWORD "lukas"

/*
 * Certain arguments differ when using VIX with VMware Server 2.0
 * and VMware Workstation.
 *
 * Comment out this definition to use this code with VMware Server 2.0.
 */
#define USE_WORKSTATION

#ifdef USE_WORKSTATION

#define  CONNTYPE    VIX_SERVICEPROVIDER_VMWARE_WORKSTATION

#define  HOSTNAME ""
#define  HOSTPORT 0
#define  USERNAME ""
#define  PASSWORD ""

#define  VMPOWEROPTIONS   VIX_VMPOWEROP_LAUNCH_GUI

#define VMXPATH_INFO "where vmxpath is an absolute path to the .vmx file " \
                     "for the virtual machine."

#else    // USE_WORKSTATION

/*
 * For VMware Server 2.0
 */

#define CONNTYPE VIX_SERVICEPROVIDER_VMWARE_VI_SERVER

#define HOSTNAME "https://192.2.3.4:8333/sdk"
/*
 * NOTE: HOSTPORT is ignored, so the port should be specified as part
 * of the URL.
 */
#define HOSTPORT 0
#define USERNAME "root"
#define PASSWORD "hideme"

#define  VMPOWEROPTIONS VIX_VMPOWEROP_NORMAL

#define VMXPATH_INFO "where vmxpath is a datastore-relative path to the " \
                     ".vmx file for the virtual machine, such as "        \
                     "\"[standard] ubuntu/ubuntu.vmx\"."

#endif    // USE_WORKSTATION

#define  TOOLS_TIMEOUT  300

#ifdef _WIN32
#define DEST_FILE ".\\outfile"
#else
#define DEST_FILE "./outfile.txt"
#endif

static char *progname;

static void
usage()
{
   fprintf(stderr, "Usage: %s <vmxpath>\n", progname);
   fprintf(stderr, "%s\n", VMXPATH_INFO);
}

int
main(int argc, char **argv)
{
   char       *vmpath;
   VixError    err;
   VixHandle   hostHandle = VIX_INVALID_HANDLE;
   VixHandle   jobHandle = VIX_INVALID_HANDLE;
   VixHandle   vmHandle = VIX_INVALID_HANDLE;
   progname = argv[0];
   if (argc > 1)
   {
	   vmpath = argv[1];
   }
   else {
      usage();
      exit(EXIT_FAILURE);
   }

   jobHandle = VixHost_Connect(VIX_API_VERSION,          // api version
                               CONNTYPE,                 // connection type
                               HOSTNAME,                 // host name
                               HOSTPORT,                 // host port
                               USERNAME,                 // username
                               PASSWORD,                 // passwd
                               0,                        // options
                               VIX_INVALID_HANDLE,       // property list handle
                               NULL,                     // callback
                               NULL);                    // client data

   err = VixJob_Wait(jobHandle, VIX_PROPERTY_JOB_RESULT_HANDLE, 
                     &hostHandle, VIX_PROPERTY_NONE);
   Vix_ReleaseHandle(jobHandle);
   if (VIX_FAILED(err)) {
      fprintf(stderr, "failed to connect to host (%"FMT64"d %s)\n", err,
	      Vix_GetErrorText(err, NULL));
      goto abort;
   }
   printf("connected to host (%d)\n", hostHandle);

   printf ("about to open %s\n", vmpath);
   jobHandle = VixVM_Open(hostHandle,               // host connection
                          argv[1],                   // path to vmx
                          NULL,                     // callback
                          NULL);                    // client data
   err = VixJob_Wait(jobHandle, VIX_PROPERTY_JOB_RESULT_HANDLE,
                     &vmHandle, VIX_PROPERTY_NONE);
   Vix_ReleaseHandle(jobHandle);
   if (VIX_FAILED(err)) {
      fprintf(stderr, "failed to open virtual machine '%s'(%"FMT64"d %s)\n", vmpath, err,
	      Vix_GetErrorText(err, NULL));
      goto abort;
   }
   printf ("opened %s (%d)\n", vmpath, vmHandle);

   printf("powering on\n");
   jobHandle = VixVM_PowerOn(vmHandle,                 // vm handle
                             VMPOWEROPTIONS,           // options
                             VIX_INVALID_HANDLE,       // property list
                             NULL,                     // callback
                             NULL);                    // client data
   err = VixJob_Wait(jobHandle, VIX_PROPERTY_NONE);
   Vix_ReleaseHandle(jobHandle);
   if (VIX_FAILED(err)) {
         fprintf(stderr, "failed to power on virtual machine (%"FMT64"d %s)\n", err,
                 Vix_GetErrorText(err, NULL));
         goto abort;
   }
   printf("powered on\n");

   printf("waiting for tools\n");
   jobHandle = VixVM_WaitForToolsInGuest(vmHandle,
                                         TOOLS_TIMEOUT,     // timeout in secs
                                         NULL,              // callback
                                         NULL);             // client data
   err = VixJob_Wait(jobHandle, VIX_PROPERTY_NONE);
   Vix_ReleaseHandle(jobHandle);
   if (VIX_FAILED(err)) {
         fprintf(stderr, "failed to wait for tools in virtual machine (%"FMT64"d %s)\n", err,
                 Vix_GetErrorText(err, NULL));
         goto abort;
   }
   printf("tools up\n");

   jobHandle = VixVM_LoginInGuest(vmHandle,
                                  argv[2],           // guest OS user
                                  argv[3],           // guest OS passwd
                                  0,                        // options
                                  NULL,                     // callback
                                  NULL);                    // client data
   err = VixJob_Wait(jobHandle, VIX_PROPERTY_NONE);
   Vix_ReleaseHandle(jobHandle);
   if (VIX_FAILED(err)) {
      fprintf(stderr, "failed to login to virtual machine '%s'(%"FMT64"d %s)\n", vmpath, err,
	      Vix_GetErrorText(err, NULL));
      goto abort;
   }
   printf("logged in to guest\n");

   printf("about to do work\n");

   char *inter;
   inter = "asd";
   if (strcmp(argv[4], "bash") == 0)
   {
	   inter= "/bin/bash";
   }
   else if (strcmp(argv[4], "python") == 0)
   {
	   inter="/usr/bin/python3.7";
   }
   else if (strcmp(argv[4], "perl") == 0)
   {
	  inter= "/usr/bin/perl";
   }
   else if (strcmp(argv[4], "php") == 0)
   {
	   inter="/usr/bin/php";
   }
   else if (strcmp(argv[4], "shell") == 0)
   {
	   inter="shell";
   }
   FILE* f;
   char* string;
   long fsize;
   if (strcmp(inter, "shell") == 0)
   {
	   printf("%s\n", argv[5]);
	   jobHandle = VixVM_RunProgramInGuest(vmHandle,
		   argv[5],                // command
		   " /bin > /tmp/outfile",   // cmd args
		   0,                        // options
		   VIX_INVALID_HANDLE,       // prop handle
		   NULL,                     // callback
		   NULL);                    // client data
	   err = VixJob_Wait(jobHandle, VIX_PROPERTY_NONE);
	   Vix_ReleaseHandle(jobHandle);
	   if (VIX_FAILED(err)) {
		   fprintf(stderr, "failed to run program in virtual machine '%s'(%"FMT64"d %s)\n",
			   vmpath, err, Vix_GetErrorText(err, NULL));
		   goto abort;
	   }
	   jobHandle = VixVM_CopyFileFromGuestToHost(vmHandle,
		   "/tmp/outfile",     // src file
		   "./outfile",          // dst file
		   0,                  // options
		   VIX_INVALID_HANDLE, // prop list
		   NULL,               // callback
		   NULL);              // client data
	   err = VixJob_Wait(jobHandle, VIX_PROPERTY_NONE);
	   Vix_ReleaseHandle(jobHandle);
	   if (VIX_FAILED(err)) {
		   fprintf(stderr, "failed to copy file to the host '%s'(%"FMT64"d %s)\n",
			   vmpath, err, Vix_GetErrorText(err, NULL));
		   goto abort;
	   }

	   jobHandle = VixVM_DeleteFileInGuest(vmHandle,
		   "/tmp/outfile",           // filepath
		   NULL,                     // callback
		   NULL);                    // client data
	   err = VixJob_Wait(jobHandle, VIX_PROPERTY_NONE);
	   Vix_ReleaseHandle(jobHandle);
	   if (VIX_FAILED(err)) {
		   fprintf(stderr, "failed to delete file in virtual machine '%s'(%"FMT64"d %s)\n",
			   vmpath, err, Vix_GetErrorText(err, NULL));
		   goto abort;
	   }
   }
   else
   {
	   f = fopen(argv[5], "rb");
	   fseek(f, 0, SEEK_END);
	   fsize = ftell(f);
	   fseek(f, 0, SEEK_SET);  /* same as rewind(f); */

	   string = malloc(fsize + 1);
	   fread(string, 1, fsize, f);
	   fclose(f);

	   string[fsize] = 0;
	   jobHandle = VixVM_RunScriptInGuest(vmHandle,
		   inter,
		   string,
		   0, // options,
		   VIX_INVALID_HANDLE, // propertyListHandle,
		   NULL, // callbackProc,
		   NULL); // clientData

	   err = VixJob_Wait(jobHandle, VIX_PROPERTY_NONE);

	   if (VIX_OK != err) {
		   printf("can't run\n");
		   goto abort;
	   }
	   jobHandle = VixVM_CopyFileFromGuestToHost(vmHandle,
		   "/tmp/std.out",     // src file
		   DEST_FILE,          // dst file
		   0,                  // options
		   VIX_INVALID_HANDLE, // prop list
		   NULL,               // callback
		   NULL);              // client data
	   err = VixJob_Wait(jobHandle, VIX_PROPERTY_NONE);
	   Vix_ReleaseHandle(jobHandle);
	   if (VIX_FAILED(err)) {
		   fprintf(stderr, "failed to copy file to the host '%s'(%"FMT64"d %s)\n",
			   vmpath, err, Vix_GetErrorText(err, NULL));
		   goto abort;
	   }

	   jobHandle = VixVM_DeleteFileInGuest(vmHandle,
		   "/tmp/std.out",           // filepath
		   NULL,                     // callback
		   NULL);                    // client data
	   err = VixJob_Wait(jobHandle, VIX_PROPERTY_NONE);
	   Vix_ReleaseHandle(jobHandle);
	   if (VIX_FAILED(err)) {
		   fprintf(stderr, "failed to delete file in virtual machine '%s'(%"FMT64"d %s)\n",
			   vmpath, err, Vix_GetErrorText(err, NULL));
		   goto abort;
	   }
   }
   f = fopen("outfile", "rb");
   fseek(f, 0, SEEK_END);
   fsize = ftell(f);
   fseek(f, 0, SEEK_SET);  /* same as rewind(f); */

   string = malloc(fsize + 1);
   fread(string, 1, fsize, f);
   fclose(f);

   string[fsize] = 0;
   printf("%s", string);
   remove("outfile");

 abort:
   Vix_ReleaseHandle(vmHandle);
   VixHost_Disconnect(hostHandle);
   
   return 0;
}
