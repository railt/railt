%token T_TYPE                   type\b
%token T_NUM                    (\d+)
%token T_SUM                    \+
%token T_NAME                   ([_A-Za-z][_0-9A-Za-z]+)

%skip T_WHITESPACE              (\xfe\xff|\x20|\x09|\x0a|\x0d)+
%skip T_COMMENT                 #[^\n]*

#Rule:
    Sum()

#Sum:
    <T_NUM> ::T_SUM:: (<T_NUM> | Sum())

