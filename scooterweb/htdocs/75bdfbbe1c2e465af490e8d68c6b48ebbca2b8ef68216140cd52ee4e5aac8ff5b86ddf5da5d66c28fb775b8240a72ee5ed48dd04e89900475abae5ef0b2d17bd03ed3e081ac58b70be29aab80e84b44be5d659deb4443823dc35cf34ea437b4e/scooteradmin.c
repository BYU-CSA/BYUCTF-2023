#include <ctype.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <sys/types.h>
#include <sys/stat.h>
#include <fcntl.h>

#define PICSDIR "pics/"

__attribute__((constructor)) void flush_buf() {
    setbuf(stdin, NULL);
    setbuf(stdout, NULL);
    setbuf(stderr, NULL);
}

void fetchfile(){
    char buf[BUFSIZ];
    char fetchstring[BUFSIZ];
    printf("Unimplemented-work-in-progress:\nGive me a valid URL and I'll upload that file to the website for you:  ");
    if ((fgets(buf, BUFSIZ-40, stdin) == NULL)  || (strlen(buf) == 0)){
        printf("No File specified.\n");
        return;
    }
    buf[strcspn(buf, "\r\n")] = 0;
    memset(fetchstring,0,BUFSIZ);
    // todo: wget/fetch/curl the file - I'll have to hit stackoverflow for sample code 
    // snprintf(fetchstring, .....); libcurl-call-to-fetch(fetchstring);
    strncpy(fetchstring, buf, BUFSIZ-1);
    //curl_easy_setopt(h, CURLOPT_URL, fetchstringa);
    //...
    //curl_easy_perform(h);

    // -- dump the URL for debugging
    printf("DEBUG: You asked for: ");
    printf(fetchstring);
}

void printlisting(char *dir) {
    char cmd[200];
    snprintf(cmd, 200, "/bin/ls %s", dir);
    system(cmd);
}

void printmenu() {
    printf("Menu:\n");
    printf("1. List Files\n");
    printf("2. Remove File\n");
    printf("3. Add File\n");
    printf("4. A Suprise!\n");
    printf("5. Exit\n");
    printf("Enter choice: ");
}

void mainloop() {
    // I'm tired of unbuffered IO - everything after auth is buffered now.
    // (ctf=note: I've seen worse in real code.  - man this brings back bad memories of yore - I never
    // want to code C/C++ professionally again)
    char choice[4] = "";
    char *r = choice;
    char scoot[]=
"     O \n"
"    /\\_ \n"
"   /_  `\\D \n"
"  ,===/  || \n"
" /___/__ |_\\ \n"
"  (o)    (o) \n";
    while (1) {
        printmenu();
        if ((!r) || (choice[0] == EOF)) {
            exit(0);
        }
        // depending on platform I might get \r or \n or both, leave room
        while(r = fgets(choice, 10, stdin)) {
            choice[1] = 0;
            if (isdigit(choice[0])) {
                int num = choice[0] - '0';
                if (num >= 1 && num <= 5) {
                        switch(num) {
                        case 1:
                            printlisting(PICSDIR);
                            break;
                        case 2:
                            printf("Unimplemented!  (wait scooter fans, I'll get there)\n");
                            break;
                        case 3:
                            fetchfile();
                            break;
                        case 4:
                            puts(scoot);
                            break;
                        case 5:
                            return;
                            break;
                    }
                puts("\n");
                break;
                }
            }
            else
            {
                printf("Invalid choice. Please enter a number between 1 and 5: \n");
            }
            printmenu();
        }
    }
}

struct con_data_t {
    char *error;
    char creds[32];
    char userinput[32];
    int pwfile;
    int acc;
};

int check_auth(struct con_data_t *con) {
    int ret;
    char prompt[]="ScooterAdminPortal - Please enter your password: ";
    static char error[]="ScooterAdminPortal: Internal Error\n";
    static char fail[]="ScooterAdminPortal:  Access Denied. Wrong Password or System Disabled\n";
    ret = write(1, prompt, strlen(prompt));
    if (ret != strlen(prompt)) { 
        con->error = error; 
        return -1;
    } 
    ret = read(0, con->userinput, 0x32 );
    if (ret == 0) { 
        con->error = error; 
        return -1;
    } 
    con->userinput[strcspn(con->userinput, "\r\n")] = 0;
    ret = read(con->pwfile, con->creds, 0x32);
    if (ret == 0) { 
        con->error = error; 
        return -1;
    } 
    if (strcmp(con->creds,con->userinput) != 0) {
        con->error = fail; 
        return -1;
    } 
    con->error = NULL;
    return 0;
}

char *check_access(char *fname) {
    static char nocreds[]="Error: Unable to read creds file.";
    if (access(fname, R_OK)  == -1) {
        return nocreds;
    }
    return NULL; 
}

int main(int argc, char **argv, char **envp) {
    char flag1[64], flag2[64], *r;
    FILE *f;

    f = fopen ("flag1.txt", "rb");
    if (!f || !fgets(flag1, 64, f)) {
        printf("Error reading flag1\n");
        exit(1);
    }
    fclose(f);

    f = fopen ("flag2.txt", "rb");
    if (!f || !fgets(flag2, 64, f)) {
        printf("Error reading flag1\n");
        exit(1);
    }
    fclose(f);

    char granted[]="ScooterAdmin:  Access Granted. Welcome Admin.\n\n";
    char credsfile[]="creds.txt";
    struct con_data_t con;
    char *err;

    if ((err = check_access(credsfile)) != NULL){
        write(2, err, strlen(err));
        exit(1);
    }
    con.pwfile = open(credsfile, O_RDONLY);

    if ( check_auth(&con) != 0 ){
        write(2, con.error, strlen(con.error));
        exit(1);
    }

    write(1, granted, strlen(granted));
    write(1, flag1, strlen(flag1));
    mainloop();
    exit(0);
}

